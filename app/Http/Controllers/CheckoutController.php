<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\PaymentFunnelService;
use App\Services\PaymentGatewayService;
use App\Models\Transaction;
use App\Models\Website;
use App\Mail\TransactionInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    protected CartService $cartService;
    protected PaymentFunnelService $paymentFunnelService;

    public function __construct(CartService $cartService, PaymentFunnelService $paymentFunnelService)
    {
        $this->cartService = $cartService;
        $this->paymentFunnelService = $paymentFunnelService;
    }

    /**
     * Show checkout page
     */
    public function show()
    {
        // Store intended URL for redirect after login if guest accesses checkout directly
        if (!Auth::check()) {
            session(['url.intended' => url()->current()]);
        }
        
        // Get current cart
        $cart = $this->cartService->getCart();

        // Redirect to shop if cart is empty
        if (empty($cart['items'])) {
            return redirect('/')->with('info', 'Please add items to your cart first');
        }

        // Validate cart items
        $validation = $this->cartService->validateForCheckout();
        if (!$validation['valid']) {
            return redirect('/')->with('error', $validation['message']);
        }

        // Get user if logged in
        $user = Auth::user();

        // Build checkout data
        $checkoutData = [
            'cart' => $cart,
            'user' => $user,
            'itemCount' => $cart['item_count'],
            'total' => $cart['total'],
            'items' => $this->formatCheckoutItems($cart['items']),
            'requiresEmail' => !$user,
            'csrfToken' => csrf_token()
        ];

        return view('checkout', $checkoutData);
    }

    /**
     * Process checkout and prepare for payment
     */
    public function process(Request $request)
    {
        // Validate required payment information
        $validated = $request->validate([
            'email' => 'required|email',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'payment_method' => 'required|in:stripe,authorize_net,coinbase',
            'payment_token' => 'required_unless:payment_method,coinbase|string|nullable',
            // Optional: address fields
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip' => 'nullable|string',
            'country' => 'nullable|string'
        ]);

        // Get cart
        $cart = $this->cartService->getCart();
        
        if (empty($cart['items'])) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty'
            ], 400);
        }

        // Validate cart again
        $validation = $this->cartService->validateForCheckout();
        if (!$validation['valid']) {
            return response()->json([
                'success' => false,
                'message' => $validation['message']
            ], 400);
        }

        try {
            // Transform cart to checkout data
            $checkoutData = $this->cartService->getCheckoutData();

            // Normalize totals and attach formatted items for downstream processing
            $checkoutData['total'] = $checkoutData['total_amount'] ?? $cart['total'];
            $checkoutData['items'] = $this->formatCheckoutItems($cart['items']);

            // Guard against zero/negative totals
            if (($checkoutData['total'] ?? 0) <= 0) {
                return $this->handlePaymentFailure('Cart total must be greater than zero', $checkoutData);
            }

            // Carry optional tip data if present
            $checkoutData['tip_amount'] = (float)$request->input('tip_amount', 0);
            $checkoutData['tip_percentage'] = (float)$request->input('tip_percentage', 0);
            $checkoutData['tip_enabled'] = (bool)$request->input('tip_enabled', false);

            // Calculate processing fee BEFORE payment
            $websiteId = $this->resolveWebsiteId();
            $processingFeePercentage = $this->getProcessingFeePercentage($websiteId);
            $processingFee = ($checkoutData['total'] / 100) * $processingFeePercentage;
            $checkoutData['processing_fee'] = round($processingFee, 2);
            
            // Calculate final charge amount including fee and tip
            $finalChargeAmount = $checkoutData['total'] + $checkoutData['processing_fee'];
            if ($checkoutData['tip_enabled']) {
                $finalChargeAmount += $checkoutData['tip_amount'];
            }
            $checkoutData['final_charge_amount'] = $finalChargeAmount;

            // Add user/payer information
            $checkoutData['email'] = $validated['email'];
            $checkoutData['first_name'] = $validated['first_name'];
            $checkoutData['last_name'] = $validated['last_name'];
            $checkoutData['address'] = $validated['address'] ?? null;
            $checkoutData['city'] = $validated['city'] ?? null;
            $checkoutData['state'] = $validated['state'] ?? null;
            $checkoutData['zip'] = $validated['zip'] ?? null;
            $checkoutData['country'] = $validated['country'] ?? null;

            // Log checkout attempt
            \Log::info('Cart Checkout Initiated', [
                'email' => $validated['email'],
                'total' => $cart['total'],
                'items' => count($cart['items']),
                'payment_method' => $validated['payment_method'],
                'session_id' => session()->getId()
            ]);

            // Track in payment funnel
            $this->paymentFunnelService->trackEvent(
                'checkout_initiated',
                'cart',
                ['item_count' => count($cart['items']), 'total' => $cart['total']]
            );

            // Route to appropriate payment processor based on payment method
            if ($validated['payment_method'] === 'stripe') {
                return $this->processStripePayment($checkoutData, $validated['payment_token']);
            } elseif ($validated['payment_method'] === 'authorize_net') {
                return $this->processAuthorizeNetPayment($checkoutData, $validated['payment_token']);
            }

            // Coinbase branch (cart)
            return $this->processCoinbasePayment($checkoutData);

        } catch (\Exception $e) {
            \Log::error('Cart Checkout Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred during checkout. Please try again.'
            ], 500);
        }
    }

    /**
     * Process cart payment via Stripe
     */
    protected function processStripePayment($checkoutData, $paymentToken)
    {
        try {
            // Resolve Stripe secret (website-specific if available)
            $website = Website::where('domain', request()->getHost())->first();
            $gatewayService = new PaymentGatewayService();
            $paymentConfig = $website ? $gatewayService->getPaymentConfigForWebsite($website) : null;
            $secretKey = $paymentConfig['config']['secret_key'] ?? config('services.stripe.secret');

            if (!$secretKey) {
                return $this->handlePaymentFailure('Stripe is not configured for this website.', $checkoutData);
            }

            // Use resolved secret
            $stripe = new \Stripe\StripeClient($secretKey);

            // Create charge
            $charge = $stripe->charges->create([
                'amount' => (int)(($checkoutData['final_charge_amount'] ?? $checkoutData['total']) * 100), // Convert to cents - includes fee and tip
                'currency' => 'usd',
                'source' => $paymentToken,
                'description' => $this->buildChargeDescription($checkoutData),
                'metadata' => [
                    'type' => 'cart',
                    'item_count' => count($checkoutData['items']),
                    'email' => $checkoutData['email'],
                    'base_amount' => $checkoutData['total'],
                    'processing_fee' => $checkoutData['processing_fee'] ?? 0,
                    'tip_amount' => $checkoutData['tip_amount'] ?? 0
                ]
            ]);

            // Payment succeeded
            if ($charge->status === 'succeeded') {
                return $this->handlePaymentSuccess($checkoutData, $charge, 'stripe');
            } else {
                return $this->handlePaymentFailure('Payment declined. Please try again.', $checkoutData);
            }

        } catch (\Stripe\Exception\CardException $e) {
            return $this->handlePaymentFailure('Card declined: ' . $e->getError()->message, $checkoutData);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return $this->handlePaymentFailure('Payment error: ' . $e->getMessage(), $checkoutData);
        } catch (\Exception $e) {
            return $this->handlePaymentFailure('An unexpected error occurred', $checkoutData);
        }
    }

    /**
     * Process cart payment via Authorize.net
     */
    protected function processAuthorizeNetPayment($checkoutData, $paymentToken)
    {
        try {
            // Use Authorize.net API
            // For now, delegate to AuthorizeNetController with cart type
            $request = new Request([
                'type' => 'cart',
                'payment_token' => $paymentToken,
                'cart_data' => $checkoutData,
                'amount' => $checkoutData['final_charge_amount'], // Use final amount with fee and tip
                'base_amount' => $checkoutData['total'],
                'processing_fee' => $checkoutData['processing_fee'] ?? 0,
                'email' => $checkoutData['email'],
                'first_name' => $checkoutData['first_name'],
                'last_name' => $checkoutData['last_name'],
                'card_number' => request()->input('card_number'),
                'expiration_date' => request()->input('expiration_date'),
                'cvv' => request()->input('cvv'),
                'name_on_card' => request()->input('name_on_card'),
                'address' => $checkoutData['address'] ?? null,
                'city' => $checkoutData['city'] ?? null,
                'state' => $checkoutData['state'] ?? null,
                'zip' => $checkoutData['zip'] ?? null,
                'country' => $checkoutData['country'] ?? null,
                'tip_amount' => $checkoutData['tip_amount'] ?? 0,
                'tip_percentage' => $checkoutData['tip_percentage'] ?? 0,
                'tip_enabled' => $checkoutData['tip_enabled'] ?? false,
            ]);

            $authorizeNetController = new AuthorizeNetController();
            $response = $authorizeNetController->processCartPayment($request);
            
            if ($response['success'] ?? false) {
                return $this->handlePaymentSuccess($checkoutData, $response, 'authorize_net');
            } else {
                return $this->handlePaymentFailure($response['message'] ?? 'Payment failed', $checkoutData);
            }

        } catch (\Exception $e) {
            return $this->handlePaymentFailure('Payment processing error: ' . $e->getMessage(), $checkoutData);
        }
    }

    /**
     * Handle successful payment
     */
    protected function handlePaymentSuccess($checkoutData, $paymentResponse, $paymentMethod)
    {
        try {
            \Log::info('Payment Success - Starting transaction recording', [
                'payment_method' => $paymentMethod,
                'items_count' => count($checkoutData['items'] ?? []),
                'total' => $checkoutData['total'] ?? 0
            ]);

            // Track each item in payment funnel
            foreach ($checkoutData['items'] as $item) {
                $this->paymentFunnelService->trackEvent(
                    'payment_complete',
                    $item['type'],
                    [
                        'item_id' => $item['id'],
                        'item_name' => $item['name'],
                        'amount' => $item['amount'],
                        'payment_method' => $paymentMethod,
                        'transaction_id' => $paymentResponse->id ?? $paymentResponse['transaction_id'] ?? null
                    ]
                );
            }

            // Persist itemized transactions sharing the same transaction id
            $transactionId = $paymentResponse->id ?? $paymentResponse['transaction_id'] ?? $paymentResponse['id'] ?? null;
            
            \Log::info('Recording cart transactions', [
                'transaction_id' => $transactionId,
                'items_count' => count($checkoutData['items'] ?? [])
            ]);
            
            $this->recordCartTransactions($checkoutData, $transactionId, $paymentMethod);

            \Log::info('Transactions recorded successfully');

            // Send transaction emails after all records are created
            $this->sendTransactionEmails($checkoutData, $transactionId);

            // Clear cart after successful payment
            $this->cartService->clearCart();

            // Calculate grand total including processing fee and tip
            $processingFeePercentage = $this->getProcessingFeePercentage($checkoutData['website_id'] ?? $this->resolveWebsiteId());
            $processingFee = $checkoutData['processing_fee'] ?? (($checkoutData['total'] / 100) * $processingFeePercentage);
            $tipAmount = $checkoutData['tip_amount'] ?? 0;
            $grandTotal = $checkoutData['total'] + $processingFee + $tipAmount;
            
            // Store transaction info for order/receipt
            session(['last_transaction' => [
                'payment_method' => $paymentMethod,
                'total' => $grandTotal,
                'subtotal' => $checkoutData['total'],
                'processing_fee' => $processingFee,
                'tip_amount' => $tipAmount,
                'items' => $checkoutData['items'],
                'email' => $checkoutData['email'],
                'timestamp' => now()
            ]]);

            \Log::info('Payment Success - Complete', ['redirect_url' => route('checkout.success')]);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful!',
                'redirect' => route('checkout.success')
            ]);

        } catch (\Exception $e) {
            \Log::error('Payment Success Handler Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'items_count' => count($checkoutData['items'] ?? [])
            ]);

            // Even if logging fails, payment succeeded
            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'redirect' => route('checkout.success')
            ]);
        }
    }

    /**
     * Handle payment failure
     */
    protected function handlePaymentFailure($message, $checkoutData = null)
    {
        // Track failed payment attempt
        if ($checkoutData) {
            $this->paymentFunnelService->trackEvent(
                'payment_failed',
                'cart',
                [
                    'message' => $message,
                    'item_count' => count($checkoutData['items'] ?? [])
                ]
            );
        }

        return response()->json([
            'success' => false,
            'message' => $message
        ], 400);
    }

    /**
     * Show checkout success page
     */
    public function success()
    {
        $transaction = session('last_transaction');

        if (!$transaction) {
            return redirect('/')->with('info', 'No recent transaction');
        }

        return view('checkout-success', ['transaction' => $transaction]);
    }

    /**
     * Format cart items for display/processing
     */
    protected function formatCheckoutItems($items)
    {
        $formatted = [];

        foreach ($items as $key => $item) {
            $formatted[] = [
                'key' => $key,
                'type' => $item['type'],
                'id' => $item['id'],
                'name' => $item['name'],
                'quantity' => $item['quantity'] ?? 1,
                'amount' => $item['amount'] ?? $item['price'] ?? 0,
                'total' => $this->calculateItemTotal($item),
                'description' => $this->getItemDescription($item),
                'student_id' => $item['student_id'] ?? null // Preserve student_id for student donations
            ];
        }

        return $formatted;
    }

    /**
     * Calculate item total
     */
    protected function calculateItemTotal($item)
    {
        $price = 0;

        if ($item['type'] === 'student') {
            $price = $item['amount'] ?? 0;
        } else if ($item['type'] === 'ticket' || $item['type'] === 'product') {
            $price = ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        } else if ($item['type'] === 'auction') {
            $price = ($item['current_bid'] ?? $item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }

        return $price;
    }

    /**
     * Get human-readable item description
     */
    protected function getItemDescription($item)
    {
        switch ($item['type']) {
            case 'student':
                return "Donation to {$item['name']}";
            case 'ticket':
                return "Ticket: {$item['name']}";
            case 'auction':
                return "Auction Item: {$item['name']}";
            case 'product':
                return "Product: {$item['name']}";
            default:
                return $item['name'];
        }
    }

    /**
     * Build charge description for payment processor
     */
    protected function buildChargeDescription($checkoutData)
    {
        $itemTypes = [];
        foreach ($checkoutData['items'] as $item) {
            $itemTypes[] = $item['type'];
        }

        $types = array_unique($itemTypes);
        $description = 'Charity Purchase: ' . implode(', ', $types);

        if (strlen($description) > 1000) {
            $description = 'Charity Purchase - ' . count($checkoutData['items']) . ' items';
        }

        return $description;
    }

    /**
     * Persist per-item transaction records sharing a single transaction id
     * Distributes tip and processing fee proportionally across items
     */
    protected function recordCartTransactions(array $checkoutData, $transactionId, string $paymentMethod)
    {
        if (empty($checkoutData['items'])) {
            return;
        }

        $websiteId = $this->resolveWebsiteId();
        $tipAmount = $checkoutData['tip_amount'] ?? 0;
        $tipPercentage = $checkoutData['tip_percentage'] ?? 0;
        $tipEnabled = $checkoutData['tip_enabled'] ?? false;

        // Calculate total and processing fee for distribution
        $totalAmount = $checkoutData['total'] ?? 0;
        $processingFeePercentage = $this->getProcessingFeePercentage($websiteId);
        $totalFee = ($totalAmount / 100) * $processingFeePercentage;

        // Calculate total item amount (before fee/tip) for proportional distribution
        $itemTotalAmount = 0;
        foreach ($checkoutData['items'] as $item) {
            $itemTotalAmount += ($item['total'] ?? $item['amount'] ?? 0);
        }

        // Prevent division by zero
        if ($itemTotalAmount <= 0) {
            $itemTotalAmount = $totalAmount;
        }

        // Distribute tip and fee proportionally across items
        foreach ($checkoutData['items'] as $index => $item) {
            $itemAmount = $item['total'] ?? $item['amount'] ?? 0;
            
            // Calculate proportional share of fee and tip
            $proportionRatio = $itemTotalAmount > 0 ? $itemAmount / $itemTotalAmount : 0;
            $itemFee = round($totalFee * $proportionRatio, 2);
            $itemTip = $tipEnabled ? round($tipAmount * $proportionRatio, 2) : 0;

            $transaction = new Transaction();
            $transaction->transaction_id = $transactionId;
            $transaction->website_id = $websiteId;
            $transaction->amount = $itemAmount;
            $transaction->type = $item['type'] ?? 'general';
            $transaction->name = $checkoutData['first_name'] ?? null;
            $transaction->last_name = $checkoutData['last_name'] ?? null;
            $transaction->email = $checkoutData['email'] ?? null;
            $transaction->address = $checkoutData['address'] ?? null;
            $transaction->city = $checkoutData['city'] ?? null;
            $transaction->state = $checkoutData['state'] ?? null;
            $transaction->zip = $checkoutData['zip'] ?? null;
            $transaction->country = $checkoutData['country'] ?? null;
            $transaction->ip_address = request()->ip();
            $transaction->fee = $itemFee; // Proportional fee
            $transaction->fee_paid = 1;
            $transaction->status = 1;
            $transaction->payment_method = $paymentMethod;

            // Attach proportional tip
            if ($tipEnabled && $itemTip > 0) {
                $transaction->tip_amount = $itemTip;
                $transaction->tip_percentage = $tipPercentage;
            }

            // Create Donation record for donations (student or general)
            if ($item['type'] === 'student' || $item['type'] === 'general') {
                try {
                    $donation = new \App\Models\Donation();
                    $donation->amount = $itemAmount;
                    $donation->type = $item['type'];
                    $donation->website_id = $websiteId;
                    $donation->first_name = $checkoutData['first_name'] ?? null;
                    $donation->last_name = $checkoutData['last_name'] ?? null;
                    $donation->email = $checkoutData['email'] ?? null;
                    $donation->status = 1;
                    $donation->transaction_id = $transactionId;
                    
                    // For student donations, store student_id as user_id
                    if ($item['type'] === 'student') {
                        $donation->user_id = $item['id'];
                    }
                    
                    // Add tip info to donation
                    if ($tipEnabled && $itemTip > 0) {
                        $donation->tip_amount = $itemTip;
                        $donation->tip_percentage = $tipPercentage;
                        $donation->tip_enabled = true;
                    }
                    
                    $donation->save();
                    
                    // Set reference_id to donation id for donations
                    $transaction->reference_id = $donation->id;
                } catch (\Exception $e) {
                    \Log::error('Failed to create Donation record in cart checkout', [
                        'error' => $e->getMessage(),
                        'item_type' => $item['type'],
                        'item_id' => $item['id'] ?? null,
                        'stack_trace' => $e->getTraceAsString()
                    ]);
                    // Continue with transaction even if donation creation fails
                    $transaction->reference_id = $item['id'] ?? null;
                }
            } else {
                // For non-donation items (tickets, auction, etc.), reference_id is the item id
                $transaction->reference_id = $item['id'] ?? null;
            }

            try {
                $transaction->save();
            } catch (\Exception $e) {
                \Log::error('Failed to save Transaction in cart checkout', [
                    'error' => $e->getMessage(),
                    'transaction_id' => $transactionId,
                    'item_type' => $item['type'],
                    'stack_trace' => $e->getTraceAsString()
                ]);
                throw $e; // Re-throw to be caught by outer handler
            }
        }
    }

    /**
     * Get processing fee percentage for website
     */
    protected function getProcessingFeePercentage($websiteId)
    {
        try {
            if ($websiteId) {
                $website = Website::find($websiteId);
                if ($website && method_exists($website, 'getProcessingFee')) {
                    return $website->getProcessingFee();
                }
            }
            // Fallback to default
            return 2.9;
        } catch (\Exception $e) {
            return 2.9;
        }
    }

    /**
     * Resolve website id from current domain
     */
    protected function resolveWebsiteId()
    {
        try {
            $domain = request()->getHost();
            $website = Website::where('domain', $domain)->first();
            return $website?->id;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Process cart payment via Coinbase (creates hosted charge)
     */
    protected function processCoinbasePayment($checkoutData)
    {
        try {
            $websiteId = $this->resolveWebsiteId();
            $service = app(\App\Services\CoinbaseCommerceService::class);

            $chargeData = [
                'name' => 'Cart Purchase',
                'description' => $this->buildChargeDescription($checkoutData),
                'amount' => number_format($checkoutData['total'] ?? 0, 2, '.', ''),
                'currency' => 'USD',
                'metadata' => [
                    'type' => 'cart',
                    'item_count' => count($checkoutData['items'] ?? []),
                    'website_id' => $websiteId,
                    'session_id' => session()->getId(),
                ],
            ];

            $result = $service->createCharge($chargeData);

            if (!$result['success']) {
                return $this->handlePaymentFailure($result['error'] ?? 'Coinbase payment failed', $checkoutData);
            }

            $charge = $result['data'];

            // Do not clear cart yet; rely on webhook for final confirmation.
            return response()->json([
                'success' => true,
                'redirect' => $service->getCheckoutUrl($charge['code']),
                'message' => 'Redirecting to Coinbase',
            ]);

        } catch (\Exception $e) {
            return $this->handlePaymentFailure('Coinbase payment error: ' . $e->getMessage(), $checkoutData);
        }
    }

    /**
     * Send invoice emails after successful transaction
     * Dynamically sends to customer and website-configured emails based on preferences
     * Includes ALL items in the transaction, not just the first one
     */
    protected function sendTransactionEmails($checkoutData, $transactionId)
    {
        try {
            // Get the website for this transaction
            $websiteId = $this->resolveWebsiteId();
            $website = Website::find($websiteId);

            // Get ALL transaction records created for this transaction ID
            $transactions = Transaction::where('transaction_id', $transactionId)
                ->where('website_id', $websiteId)
                ->get();

            if ($transactions->isEmpty()) {
                \Log::warning('No transactions found for email sending', [
                    'transaction_id' => $transactionId,
                    'website_id' => $websiteId
                ]);
                return;
            }

            // Use first transaction for customer email (all have same email)
            $customerEmail = $transactions->first()->email;
            $customerTransaction = $transactions->first();

            // Apply website-specific mail configuration
            if ($website) {
                \App\Services\WebsiteMailService::applyForWebsite($website);
            }

            // Send to customer's email with ALL items in the transaction
            if ($customerEmail !== 'admin@admin') {
                Mail::to($customerEmail)->send(new TransactionInvoice($transactions, $website, true));
            }

            // Also send to website owner emails that have transaction preference enabled
            if ($website) {
                $websiteEmails = $website->getTransactionEmails();
                foreach ($websiteEmails as $email) {
                    if ($email !== $customerEmail && $email !== 'admin@admin') {  // Don't send duplicate if customer email is in list, skip admin@admin
                        Mail::to($email)->send(new TransactionInvoice($transactions, $website, false));
                    }
                }
            }

            \Log::info('Transaction emails sent successfully', [
                'transaction_id' => $transactionId,
                'customer_email' => $customerEmail,
                'item_count' => $transactions->count(),
                'website_id' => $websiteId
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to send transaction invoices', [
                'transaction_id' => $transactionId,
                'email' => $checkoutData['email'] ?? null,
                'error' => $e->getMessage()
            ]);
        }
    }
}
