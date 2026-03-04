<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use App\Models\PaymentSetting;
use App\Models\Donation;
use App\Models\TicektSell;
use App\Models\Transaction;
use App\Models\Ticket;
use App\Models\Auction;
use App\Models\Website;
use App\Models\Page;
use App\Models\Setting;
use App\Services\PaymentGatewayService;
use App\Services\PaymentFunnelService;
use App\Services\PushNotificationService;
use App\Mail\TransactionInvoice;
use Illuminate\Support\Facades\Mail;
use Stripe;
use Auth;

class AuthorizeNetController extends Controller
{
    protected $pushNotificationService;

    public function __construct()
    {
        $this->pushNotificationService = new PushNotificationService();
    }
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $type, $id): View
    {
        // Store intended URL for redirect after login if guest accesses checkout directly
        if (!\Auth::check()) {
            session(['url.intended' => url()->current()]);
        }
        
        if ($type == 'donation') {
            # code...
            $data = Donation::find($id);
        }elseif($type == 'ticket'){
            $data = TicektSell::find($id);
        }elseif($type == 'auction'){
            // dd($request->amount);
            $data = Auction::find($id);
            $data->amount = $request->amount;
        }elseif($type == 'investment'){
            $data = \App\Models\Investment::find($id);
            $data->amount = $data->investment_amount; // Set amount for payment processing
        }

        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);
        $website = Website::where('domain', $domain)->first();
        
        if (!$website) {
            abort(404, 'Website not found');
        }
        
        $paymentGatewayService = new PaymentGatewayService();
        $paymentConfig = $paymentGatewayService->getPaymentConfigForWebsite($website);
        $paymentMethod = $paymentConfig['payment_method'];
        
        // Load custom fonts for dynamic font support
        $customFonts = \App\Models\CustomFont::active()->get();

        if ($paymentMethod == 'stripe') {
            return view('stripe', compact('data', 'type', 'website', 'paymentConfig', 'customFonts'));
        } else {
            return view('authorize-net', compact('data', 'type', 'website', 'paymentConfig', 'customFonts'));
        }
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function paymentPost(Request $request)
    {

        // dd($request->all());    
        // Get website from current domain
        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);
        $website = Website::where('domain', $domain)->first();
        
        if (!$website) {
            return back()->with('error', 'Website not found');
        }
        
        $paymentGatewayService = new PaymentGatewayService();
        $paymentConfig = $paymentGatewayService->getPaymentConfigForWebsite($website);
        
        // Validate payment configuration
        $validationErrors = $paymentGatewayService->validatePaymentConfig($website);
        if (!empty($validationErrors)) {
            return back()->with('error', 'Payment configuration error: ' . implode(', ', $validationErrors));
        }

        $cardNumber = preg_replace('/[^0-9]/', '', trim($request->input('card_number')));
        $expirationInput = $request->input('expiration_date') ?? $request->input('date');
        $digits = preg_replace('/[^0-9]/', '', (string)$expirationInput);
        $expirationDate = null;
        if (strlen($digits) >= 4) {
            $expMonth = substr($digits, 0, 2);
            $expYear = substr($digits, 2);
            if (strlen($expYear) === 2) {
                $expYear = '20' . $expYear;
            }
            if (strlen($expYear) === 4 && (int)$expMonth >= 1 && (int)$expMonth <= 12) {
                $expirationDate = $expYear . '-' . $expMonth;
            }
        }
        if (!$expirationDate && !empty($expirationInput)) {
            $expirationDate = \Carbon\Carbon::parse($expirationInput)->format('Y-m');
        }
        // dd($expirationDate);
        $cvv = preg_replace('/[^0-9]/', '', trim($request->input('cvv')));
        if (empty($expirationDate)) {
            return back()->with('error', 'Invalid expiration date');
        }
        if (strlen($cvv) < 3 || strlen($cvv) > 4) {
            return back()->with('error', 'Invalid security code');
        }

        // Use website-specific credentials instead of environment variables
        $merchantAuthentication = $paymentGatewayService->createAuthorizeNetAuth($website);
        if (!$merchantAuthentication) {
            return back()->with('error', 'Failed to initialize payment gateway');
        }

        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($cardNumber);
        $creditCard->setExpirationDate($expirationDate);
        $creditCard->setCardCode($cvv);

        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);

        // Only set billing address if minimal AVS fields are present (no dummy defaults)
        if (!empty($request->zipcode) && !empty($request->state)) {
            $billTo = new AnetAPI\CustomerAddressType();
            if ($request->first_name) $billTo->setFirstName($request->first_name);
            if ($request->last_name) $billTo->setLastName($request->last_name);
            if ($request->zipcode) $billTo->setZip($request->zipcode);
            if ($request->state) $billTo->setState($request->state);
            if ($request->city) $billTo->setCity($request->city);
            if ($request->address) $billTo->setAddress($request->address);
            if ($request->country) $billTo->setCountry($request->country);
        }

        // Include customer email (and optional names) in CustomerData
        if (!empty($request->email) || !empty($request->first_name) || !empty($request->last_name)) {
            $customerData = new AnetAPI\CustomerDataType();
            $customerData->setType('individual');
            if (!empty($request->email)) {
                $customerData->setEmail($request->email);
            }
            if (!empty($request->first_name) || !empty($request->last_name)) {
                $customerData->setId(trim(($request->first_name ?? '') . ' ' . ($request->last_name ?? '')));
            }
        }

        $transactionRequestType = new AnetAPI\TransactionRequestType();
        if ($request->type == 'auction') {
            $transactionRequestType->setTransactionType("authOnlyTransaction");
        } else {
            $transactionRequestType->setTransactionType("authCaptureTransaction");
        }
        $amount = number_format((float)$request->amount, 2, '.', '');
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setPayment($payment);
        if (isset($billTo)) {
            $transactionRequestType->setBillTo($billTo);
        }
        if (isset($customerData)) {
            $transactionRequestType->setCustomer($customerData);
        }

        // Pass real customer IP (only if it's a public IP; avoid local/proxy placeholders)
        $clientIp = $request->getClientIp();
        if ($clientIp && filter_var($clientIp, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            $transactionRequestType->setCustomerIP($clientIp);
        }


        $requests = new AnetAPI\CreateTransactionRequest();
        $requests->setMerchantAuthentication($merchantAuthentication);
        $requests->setRefId("ref" . time());
        $requests->setTransactionRequest($transactionRequestType);

        $controller = new AnetController\CreateTransactionController($requests);
        // Use website-specific environment (sandbox/production)
        $environment = $paymentGatewayService->getAuthorizeNetEnvironment($website);
        // dd($environment);
        $response = $controller->executeWithApiResponse($environment);

        if ($response != null) {
            $tresponse = $response->getTransactionResponse();

            // dd($response);

            if ($tresponse != null & $tresponse->getResponseCode() == "1") {
                $type = $request->type;
                if ($request->type == 'donation') {
                    # code...
                    $donation = Donation::find($request->donation_id);
                    $donation->status = 1;
                    $donation->transaction_id = $tresponse->getTransId();
                    
                    // Process tip if enabled
                    if ($request->input('tip_enabled') && $request->input('tip_amount') > 0) {
                        $donation->tip_amount = $request->input('tip_amount');
                        $donation->tip_percentage = $request->input('tip_percentage');
                        $donation->tip_enabled = true;
                    }
                    
                    $donation->update();

                    // Send push notification to website owner
                    try {
                        $donorName = trim($donation->first_name . ' ' . $donation->last_name);
                        if (empty($donorName)) {
                            $donorName = 'Anonymous Donor';
                        }
                        
                        $this->pushNotificationService->sendDonationNotification(
                            $donation->user_id,
                            $donation->amount,
                            $donorName,
                            $donation->id
                        );

                        // Check if goal is reached and send notification
                        $website = Website::find($donation->website_id);
                        if ($website) {
                            $setting = Setting::where('user_id', $website->user_id)->first();
                            if ($setting && $setting->goal > 0) {
                                // Calculate total donations
                                $totalDonations = Donation::where('website_id', $website->id)
                                    ->where('status', 1)
                                    ->sum('amount');
                                
                                // Check if we just reached the goal (within donation amount tolerance)
                                $previousTotal = $totalDonations - $donation->amount;
                                if ($previousTotal < $setting->goal && $totalDonations >= $setting->goal) {
                                    // Goal just reached!
                                    $this->pushNotificationService->sendGoalReachedNotification(
                                        $donation->user_id,
                                        $website->name ?? 'Campaign',
                                        $setting->goal
                                    );
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        \Log::error('Push notification error for donation: ' . $e->getMessage());
                    }

                    if ($donation->type == 'student') {
                        # code...

                        // Get website-specific processing fee
                        $website = \App\Models\Website::find($donation->website_id);
                        $processingFeePercentage = $website ? $website->getProcessingFee() : 2.9;
                        $fee = ($donation->amount / 100) * $processingFeePercentage;

                        $tran = new Transaction;
                        $tran->amount = $donation->amount;
                        $tran->type = $donation->type;
                        $tran->website_id = $donation->website_id;
                        $tran->transaction_id = $tresponse->getTransId();
                        $tran->name = $request->first_name;
                        $tran->last_name = $request->last_name;
                        $tran->email = $request->email;
                        $tran->address = $request->address;
                        $tran->apartment = $request->apartment;
                        $tran->city = $request->city;
                        $tran->state = $request->state;
                        $tran->zip = $request->zipcode;
                        $tran->phone = $request->phone;
                        $tran->name_on_card = $request->name_on_card;
                        $tran->country = $request->country;
                        $tran->ip_address = $request->ip();
                        $tran->fee = $fee;
                        $tran->fee_paid = 1;
                        
                        // Add tip information
                        if ($donation->tip_enabled) {
                            $tran->tip_amount = $donation->tip_amount;
                            $tran->tip_percentage = $donation->tip_percentage;
                        }

                        $tran->status = $donation->status;
                        $tran->reference_id = $donation->id; // Assuming reference_id is not provided in the request
                        $tran->save();

                        // Send invoice email and handle post-transaction operations
                        $this->afterTransactionSaved($tran, $website);

                        // Track successful payment
                        $this->trackPaymentFunnel('completed', $donation->type, $donation->amount, $tresponse->getTransId(), null, $request->input('student_id'));
                        return view('thank-you', compact('type'));
                    }elseif ($donation->type == 'general') {
                        # code...

                        // Get website-specific processing fee
                        $website = \App\Models\Website::find($donation->website_id);
                        $processingFeePercentage = $website ? $website->getProcessingFee() : 2.9;
                        $fee = ($donation->amount / 100) * $processingFeePercentage; 

                        $tran = new Transaction;
                        $tran->amount = $donation->amount;
                        $tran->type = $donation->type;
                        $tran->website_id = $donation->website_id;
                        $tran->transaction_id = $tresponse->getTransId();
                        $tran->name = $request->first_name;
                        $tran->last_name = $request->last_name;
                        $tran->email = $request->email;
                        $tran->address = $request->address;
                        $tran->apartment = $request->apartment;
                        $tran->city = $request->city;
                        $tran->state = $request->state;
                        $tran->zip = $request->zipcode;
                        $tran->phone = $request->phone;
                        $tran->name_on_card = $request->name_on_card;
                        $tran->country = $request->country;
                        $tran->fee = $fee;
                        $tran->fee_paid = 1;
                        
                        // Add tip information
                        if ($donation->tip_enabled) {
                            $tran->tip_amount = $donation->tip_amount;
                            $tran->tip_percentage = $donation->tip_percentage;
                        }
                        
                        $tran->status = $donation->status;
                        $tran->reference_id = $donation->id; // Assuming reference_id is not provided in the request
                        $tran->save();

                        // Send invoice email and handle post-transaction operations
                        $this->afterTransactionSaved($tran, $website);

                        return view('thank-you', compact('type'));
                    } else {
                        # code...
                        return redirect('/auction')->with('success', 'Payment successful!');
                    }
                }elseif($request->type == 'ticket'){
                    $donation = TicektSell::find($request->donation_id);
                    $donation->status = 1;
                    $donation->first_name = $request->first_name;
                    $donation->last_name = $request->last_name;
                    $donation->email = $request->email;
                    $donation->update();

                    // Update all ticket sell details to success status  
                    // foreach ($donation->details as $detail) {
                    //     $detail->status = 1;
                    //     $detail->save();
                    // }

                    // Get website-specific processing fee
                    $website = \App\Models\Website::find($donation->website_id);
                    $processingFeePercentage = $website ? $website->getProcessingFee() : 2.9;
                    $fee = ($donation->amount / 100) * $processingFeePercentage; 


                    $tran = new Transaction;
                    $tran->amount = $request->amount;
                    $tran->type = 'ticket';
                    $tran->website_id = $donation->website_id;
                    $tran->transaction_id = $tresponse->getTransId();
                    $tran->name = $request->first_name;
                    $tran->email = $request->email;
                    $tran->last_name = $request->last_name;
                    // $tran->email = $request->email;
                    $tran->address = $request->address;
                    $tran->apartment = $request->apartment;
                    $tran->city = $request->city;
                    $tran->state = $request->state;
                    $tran->zip = $request->zipcode;
                    $tran->phone = $request->phone;
                    $tran->name_on_card = $request->name_on_card;
                    $tran->country = $request->country;
                    $tran->fee = $fee;
                    $tran->fee_paid = 1;
                    $tran->status = $donation->status;
                    $tran->reference_id = $donation->id; // Assuming reference_id is not provided in the request
                    $tran->save();

                    // Send invoice email and handle post-transaction operations
                    $this->afterTransactionSaved($tran, $website);

                    // Track successful Authorize.Net payment for ticket purchase
                    $this->trackPaymentFunnel('completed', 'ticket', $request->amount, $tresponse->getTransId(), null, null);

                    // Send push notification to website owner
                    try {
                        if ($website && $website->user_id) {
                            $totalQuantity = $donation->details->sum('quantity');
                            $ticketNames = $donation->details->map(function($detail) {
                                $ticket = Ticket::find($detail->ticket_id);
                                return $ticket ? $ticket->name : 'Event Ticket';
                            })->unique()->implode(', ');
                            
                            $this->pushNotificationService->sendTicketPurchaseNotification(
                                $website->user_id,
                                $ticketNames,
                                $totalQuantity,
                                $donation->id
                            );
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to send ticket purchase notification: ' . $e->getMessage());
                    }

                    foreach ($donation->details as $key => $value) {
                        # code...
                        $ticket = Ticket::find($value->ticket_id);
                        
                        // For property type, update available_shares instead of quantity
                        if ($ticket->type === 'property') {
                            $ticket->available_shares -= $value->quantity;
                        } else {
                            $ticket->quantity -= $value->quantity;
                        }
                        
                        $ticket->update();
                    }

                    return view('thank-you', compact('type'));

                }elseif($request->type == 'auction'){
                    $donation = Auction::find($request->donation_id);
                    $donation->last_bid = $request->amount;
                    $donation->transaction_id = $tresponse->getTransId();
                    // $donation->email = $request->email;
                    $donation->update();

                    $del = Transaction::where('type','auction')->where('reference_id',$donation->id)->delete();

                    // Get website-specific processing fee
                    $website = \App\Models\Website::find($donation->website_id);
                    $processingFeePercentage = $website ? $website->getProcessingFee() : 2.9;
                    $fee = ($donation->amount / 100) * $processingFeePercentage; 

                    $tran = new Transaction;
                    $tran->amount = $request->amount;
                    $tran->type = 'auction';
                    $tran->website_id = $donation->website_id;
                    $tran->transaction_id = $tresponse->getTransId();
                    $tran->name = $request->first_name;
                    $tran->last_name = $request->last_name;
                    $tran->email = $request->email;
                    $tran->address = $request->address;
                    $tran->apartment = $request->apartment;
                    $tran->city = $request->city;
                    $tran->state = $request->state;
                    $tran->zip = $request->zipcode;
                    $tran->phone = $request->phone;
                    $tran->name_on_card = $request->name_on_card;
                    $tran->country = $request->country;
                    $tran->fee = $fee;
                    $tran->fee_paid = 1;
                    $tran->status = $donation->status;
                    $tran->reference_id = $donation->id; // Assuming reference_id is not provided in the request
                    $tran->save();

                    // Send invoice email and handle post-transaction operations
                    $this->afterTransactionSaved($tran, $website);

                    // Track successful Authorize.Net payment for auction
                    $this->trackPaymentFunnel('completed', 'auction', $request->amount, $tresponse->getTransId(), null, null);

                    return view('thank-you', compact('type'));

                }elseif($request->type == 'investment'){
                    $investment = \App\Models\Investment::find($request->donation_id);
                    $investment->status = 'completed';
                    $investment->transaction_id = $tresponse->getTransId();
                    $investment->update();

                    // Get website-specific processing fee
                    $website = \App\Models\Website::find($investment->website_id);
                    $processingFeePercentage = $website ? $website->getProcessingFee() : 2.9;
                    $fee = ($investment->investment_amount / 100) * $processingFeePercentage; 

                    $tran = new Transaction;
                    $tran->amount = $investment->investment_amount;
                    $tran->type = 'investment';
                    $tran->website_id = $investment->website_id;
                    $tran->transaction_id = $tresponse->getTransId();
                    $tran->name = $request->first_name;
                    $tran->last_name = $request->last_name;
                    $tran->email = Auth::user()->email;
                    $tran->address = $request->address;
                    $tran->apartment = $request->apartment;
                    $tran->city = $request->city;
                    $tran->state = $request->state;
                    $tran->zip = $request->zipcode;
                    $tran->phone = $request->phone;
                    $tran->name_on_card = $request->name_on_card;
                    $tran->country = $request->country;
                    $tran->fee = $fee;
                    $tran->fee_paid = 1;
                    $tran->status = 1; // Completed status
                    $tran->reference_id = $investment->id;
                    $tran->save();

                    // Send invoice email and handle post-transaction operations
                    $this->afterTransactionSaved($tran, $website);

                    // Send push notification to website owner (Authorize.Net investment)
                    try {
                        if ($website && $website->user_id) {
                            $this->pushNotificationService->sendInvestmentMilestoneNotification(
                                $website->user_id,
                                'New Investment Received',
                                $investment->investment_amount,
                                $investment->id
                            );
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to send investment notification: ' . $e->getMessage());
                    }

                    // Track successful Authorize.Net payment for investment
                    $this->trackPaymentFunnel('completed', 'investment', $investment->investment_amount, $tresponse->getTransId(), null, null);

                    return view('thank-you', compact('type'));

                }else {
                        # code...
                        return redirect('/')->with('success', 'Payment successful!');
                }


            } else {
                // Track payment failure
                $amount = $request->input('amount', 0);
                $type = $request->input('type', 'general');
                $this->trackPaymentFunnel('failed', $type, $amount, null, 'Payment failed - Response error');
                
                // dd($response);
                return back()->with('error', "Oops! This payment was declined or entered incorrectly. Please review your card information and try again, or use another card.");
            }
        } else {
            // Track payment failure
            $amount = $request->input('amount', 0);
            $type = $request->input('type', 'general');
            $this->trackPaymentFunnel('failed', $type, $amount, null, 'Payment failed - Transaction not approved');
            
            // dd($response);
                // dd($response);
            return back()->with('error', "Oops! This payment was declined or entered incorrectly. Please review your card information and try again, or use another card.");
        }

    }

    public function paymentStripe(Request $request)
    {
        // Log all incoming request data for debugging
        \Log::info('Stripe payment request received', [
            'all_data' => $request->all(),
            'domain' => request()->getHost(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        // Basic validation - only validate essential fields
        $request->validate([
            'stripeToken' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|string',
        ]);
        
        // Log payment attempt for debugging
        \Log::info('Stripe payment attempt validated', [
            'domain' => request()->getHost(),
            'amount' => $request->amount,
            'type' => $request->type,
            'has_token' => !empty($request->stripeToken),
            'token_preview' => substr($request->stripeToken, 0, 10) . '...'
        ]);
        
        // Get current website based on domain
        $currentDomain = request()->getHost();
        $website = Website::where('domain', $currentDomain)->first();
        
        if (!$website) {
            \Log::error('Website not found for Stripe payment', ['domain' => $currentDomain]);
            return back()->with('error', 'Website not found');
        }

        // Get Stripe credentials for this website
        $paymentGatewayService = new PaymentGatewayService();
        $paymentData = $paymentGatewayService->getPaymentConfigForWebsite($website);
        
        if (!$paymentData || !isset($paymentData['config']['secret_key'])) {
            \Log::error('Stripe not configured', [
                'website_id' => $website->id,
                'domain' => $currentDomain,
                'has_payment_data' => !empty($paymentData)
            ]);
            return back()->with('error', 'Stripe is not configured for this website');
        }

        Stripe\Stripe::setApiKey($paymentData['config']['secret_key']);

        try {
            \Log::info('Creating Stripe charge', [
                'amount' => $request->amount * 100,
                'token' => substr($request->stripeToken, 0, 10) . '...' // Log partial token for debugging
            ]);
            
            // 3️⃣ Create a one‑time token from the raw card data
            $charge = Stripe\Charge::create ([
                    "amount" => $request->amount * 100,
                    "currency" => "usd",
                    "source" => $request->stripeToken,
                    "description" => "Payment for " . ($website->name ?? 'website')
            ]);
            
            \Log::info('Stripe charge successful', [
                'charge_id' => $charge->id,
                'amount' => $charge->amount,
                'type' => $request->type
            ]);

            // dd();
            $type = $request->type;

              if ($request->type == 'donation') {
                    # code...
                    $donation = Donation::find($request->donation_id);
                    $donation->status = 1;
                    $donation->transaction_id = $charge->id;
                    
                    // Process tip if enabled
                    if ($request->input('tip_enabled') && $request->input('tip_amount') > 0) {
                        $donation->tip_amount = $request->input('tip_amount');
                        $donation->tip_percentage = $request->input('tip_percentage');
                        $donation->tip_enabled = true;
                    }
                    
                    $donation->update();

                    if ($donation->type == 'student') {
                        # code...

                        // Get website-specific processing fee
                        $website = \App\Models\Website::find($donation->website_id);
                        $processingFeePercentage = $website ? $website->getProcessingFee() : 2.9;
                        $fee = ($donation->amount / 100) * $processingFeePercentage;

                        $tran = new Transaction;
                        $tran->amount = $donation->amount;
                        $tran->type = $donation->type;
                        $tran->website_id = $donation->website_id;
                        $tran->transaction_id = $charge->id;
                        $tran->name = $request->first_name;
                        $tran->last_name = $request->last_name;
                        $tran->email = $request->email;
                        $tran->address = $request->address;
                        $tran->apartment = $request->apartment;
                        $tran->city = $request->city;
                        $tran->state = $request->state;
                        $tran->zip = $request->zipcode;
                        $tran->phone = $request->phone;
                        $tran->name_on_card = $request->name_on_card;
                        $tran->ip_address = $request->ip();
                        $tran->fee = $fee;
                        $tran->fee_paid = 1;
                        
                        // Add tip information (Stripe)
                        if ($donation->tip_enabled) {
                            $tran->tip_amount = $donation->tip_amount;
                            $tran->tip_percentage = $donation->tip_percentage;
                        }

                        $tran->status = $donation->status;
                        $tran->reference_id = $donation->id; // Assuming reference_id is not provided in the request
                        $tran->save();

                        // Send invoice email and handle post-transaction operations
                        $this->afterTransactionSaved($tran, $website);

                        // Send push notification to website owner
                        try {
                            if ($website && $website->user_id) {
                                $this->pushNotificationService->sendDonationNotification(
                                    $website->user_id,
                                    $donation->amount,
                                    $request->first_name . ' ' . $request->last_name,
                                    $donation->id
                                );
                            }
                        } catch (\Exception $e) {
                            \Log::error('Failed to send donation notification: ' . $e->getMessage());
                        }

                        // Track successful Stripe payment
                        $this->trackPaymentFunnel('completed', $donation->type, $donation->amount, $charge->id, null, $request->input('student_id'));

                        return view('thank-you', compact('type'));
                    }elseif ($donation->type == 'general') {
                        # code...

                        // Get website-specific processing fee
                        $website = \App\Models\Website::find($donation->website_id);
                        $processingFeePercentage = $website ? $website->getProcessingFee() : 2.9;
                        $fee = ($donation->amount / 100) * $processingFeePercentage; 

                        $tran = new Transaction;
                        $tran->amount = $donation->amount;
                        $tran->type = $donation->type;
                        $tran->website_id = $donation->website_id;
                        $tran->transaction_id = $charge->id;
                        $tran->name = $request->first_name;
                        $tran->last_name = $request->last_name;
                        $tran->email = $request->email;
                        $tran->address = $request->address;
                        $tran->apartment = $request->apartment;
                        $tran->city = $request->city;
                        $tran->state = $request->state;
                        $tran->zip = $request->zipcode;
                        $tran->phone = $request->phone;
                        $tran->name_on_card = $request->name_on_card;
                        $tran->country = $request->country;
                        $tran->fee = $fee;
                        $tran->fee_paid = 1;
                        
                        // Add tip information (Stripe)
                        if ($donation->tip_enabled) {
                            $tran->tip_amount = $donation->tip_amount;
                            $tran->tip_percentage = $donation->tip_percentage;
                        }
                        
                        $tran->status = $donation->status;
                        $tran->reference_id = $donation->id; // Assuming reference_id is not provided in the request
                        $tran->save();

                        // Send invoice email and handle post-transaction operations
                        $this->afterTransactionSaved($tran, $website);

                        // Send push notification to website owner (general donation - Stripe)
                        try {
                            if ($website && $website->user_id) {
                                $this->pushNotificationService->sendDonationNotification(
                                    $website->user_id,
                                    $donation->amount,
                                    $request->first_name . ' ' . $request->last_name,
                                    $donation->id
                                );
                            }
                        } catch (\Exception $e) {
                            \Log::error('Failed to send donation notification: ' . $e->getMessage());
                        }

                        return view('thank-you', compact('type'));
                    } else {
                        # code...
                        return redirect('/auction')->with('success', 'Payment successful!');
                    }
                }elseif($request->type == 'ticket'){
                    $donation = TicektSell::find($request->donation_id);
                    $donation->status = 1;
                    
                    // Only update fields if they exist in request
                    if ($request->has('first_name') && $request->first_name) {
                        $donation->first_name = $request->first_name;
                    }
                    if ($request->has('last_name') && $request->last_name) {
                        $donation->last_name = $request->last_name;
                    }
                    if ($request->has('email') && $request->email) {
                        $donation->email = $request->email;
                    }
                    
                    $donation->update();

                    // Note: ticket_sell_details table doesn't have status column
                    // Status is managed at the ticekt_sells level only
                    // foreach ($donation->details as $detail) {
                    //     $detail->status = 1;
                    //     $detail->save();
                    // }

                    // Get website-specific processing fee
                    $website = \App\Models\Website::find($donation->website_id);
                    $processingFeePercentage = $website ? $website->getProcessingFee() : 2.9;
                    $fee = ($donation->amount / 100) * $processingFeePercentage; 


                    $tran = new Transaction;
                    $tran->amount = $request->amount;
                    $tran->type = 'ticket';
                    $tran->website_id = $donation->website_id ?? $website->id;
                    $tran->transaction_id = $charge->id;
                    $tran->name = $request->input('first_name', '');
                    $tran->last_name = $request->input('last_name', '');
                    $tran->email = Auth::user()->email;
                    $tran->address = $request->input('address', '');
                    $tran->apartment = $request->input('apartment', '');
                    $tran->city = $request->input('city', '');
                    $tran->state = $request->input('state', '');
                    $tran->zip = $request->input('postalCode', ''); // Note: form uses postalCode, not zipcode
                    $tran->phone = $request->input('phone', '');
                    $tran->name_on_card = $request->input('name_on_card', '');
                    $tran->country = $request->input('country', '');
                    $tran->ip_address = $request->ip();
                    $tran->fee = $fee;
                    $tran->fee_paid = 1;
                    $tran->status = 1; // Set to 1 instead of $donation->status
                    $tran->reference_id = $donation->id; // Assuming reference_id is not provided in the request
                    $tran->save();

                    // Send invoice email and handle post-transaction operations
                    $this->afterTransactionSaved($tran, $website);

                    // Track successful Stripe payment for ticket purchase
                    $this->trackPaymentFunnel('completed', 'ticket', $request->amount, $charge->id, null, null);

                    // Send push notification to website owner
                    try {
                        if ($website && $website->user_id) {
                            $totalQuantity = $donation->details->sum('quantity');
                            $ticketNames = $donation->details->map(function($detail) {
                                $ticket = Ticket::find($detail->ticket_id);
                                return $ticket ? $ticket->name : 'Event Ticket';
                            })->unique()->implode(', ');
                            
                            $this->pushNotificationService->sendTicketPurchaseNotification(
                                $website->user_id,
                                $ticketNames,
                                $totalQuantity,
                                $donation->id
                            );
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to send ticket purchase notification: ' . $e->getMessage());
                    }

                    foreach ($donation->details as $key => $value) {
                        # code...
                        $ticket = Ticket::find($value->ticket_id);
                        
                        // For property type, update available_shares instead of quantity
                        if ($ticket->type === 'property') {
                            $ticket->available_shares -= $value->quantity;
                        } else {
                            $ticket->quantity -= $value->quantity;
                        }
                        
                        $ticket->update();
                    }

                    return view('thank-you', compact('type'));

                }elseif($request->type == 'auction'){
                    $donation = Auction::find($request->donation_id);
                    $donation->last_bid = $request->amount;
                    $donation->transaction_id = $charge->id;
                    // $donation->email = $request->email;
                    $donation->update();

                    $del = Transaction::where('type','auction')->where('reference_id',$donation->id)->delete();

                    // Get website-specific processing fee
                    $website = \App\Models\Website::find($donation->website_id);
                    $processingFeePercentage = $website ? $website->getProcessingFee() : 2.9;
                    $fee = ($donation->amount / 100) * $processingFeePercentage; 

                    $tran = new Transaction;
                    $tran->amount = $request->amount;
                    $tran->type = 'auction';
                    $tran->website_id = $donation->website_id;
                    $tran->transaction_id = $charge->id;
                    $tran->name = $request->first_name;
                    $tran->last_name = $request->last_name;
                    $tran->email = $request->email;
                    $tran->address = $request->address;
                    $tran->apartment = $request->apartment;
                    $tran->city = $request->city;
                    $tran->state = $request->state;
                    $tran->zip = $request->zipcode;
                    $tran->phone = $request->phone;
                    $tran->name_on_card = $request->name_on_card;
                    $tran->country = $request->country;
                    $tran->fee = $fee;
                    $tran->fee_paid = 1;
                    $tran->status = $donation->status;
                    $tran->reference_id = $donation->id; // Assuming reference_id is not provided in the request
                    $tran->save();

                    // Send invoice email and handle post-transaction operations
                    $this->afterTransactionSaved($tran, $website);

                    // Track successful Stripe payment for auction
                    $this->trackPaymentFunnel('completed', 'auction', $request->amount, $charge->id, null, null);

                    return view('thank-you', compact('type'));

                }elseif($request->type == 'investment'){
                    $investment = \App\Models\Investment::find($request->donation_id);
                    $investment->status = 'completed';
                    $investment->transaction_id = $charge->id;
                    $investment->update();

                    // Get website-specific processing fee
                    $website = \App\Models\Website::find($investment->website_id);
                    $processingFeePercentage = $website ? $website->getProcessingFee() : 2.9;
                    $fee = ($investment->investment_amount / 100) * $processingFeePercentage; 

                    $tran = new Transaction;
                    $tran->amount = $investment->investment_amount;
                    $tran->type = 'investment';
                    $tran->website_id = $investment->website_id;
                    $tran->transaction_id = $charge->id;
                    $tran->name = $request->first_name;
                    $tran->last_name = $request->last_name;
                    $tran->email = Auth::user()->email;
                    $tran->address = $request->address;
                    $tran->apartment = $request->apartment;
                    $tran->city = $request->city;
                    $tran->state = $request->state;
                    $tran->zip = $request->zipcode;
                    $tran->phone = $request->phone;
                    $tran->name_on_card = $request->name_on_card;
                    $tran->country = $request->country;
                    $tran->fee = $fee;
                    $tran->fee_paid = 1;
                    $tran->status = 1; // Completed status
                    $tran->reference_id = $investment->id;
                    $tran->save();

                    // Send invoice email and handle post-transaction operations
                    $this->afterTransactionSaved($tran, $website);

                    // Send push notification to website owner (Stripe investment)
                    try {
                        if ($website && $website->user_id) {
                            $this->pushNotificationService->sendInvestmentMilestoneNotification(
                                $website->user_id,
                                'New Investment Received',
                                $investment->investment_amount,
                                $investment->id
                            );
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to send investment notification: ' . $e->getMessage());
                    }

                    // Track successful Stripe payment for investment
                    $this->trackPaymentFunnel('completed', 'investment', $investment->investment_amount, $charge->id, null, null);

                    return view('thank-you', compact('type'));

                }else {
                        # code...
                        return redirect('/')->with('success', 'Payment successful!');
                }

        } catch (CardException $e) {
            // Card declined or invalid
            \Log::error('Stripe card exception', [
                'error_message' => $e->getError()->message,
                'error_code' => $e->getError()->code,
                'error_type' => $e->getError()->type,
                'amount' => $request->amount,
                'type' => $request->type,
                'request_data' => $request->all()
            ]);
            
            // Track payment failure - temporarily disabled due to string length issues
            // $amount = $request->input('amount', 0);
            // $type = $request->input('type', 'general');
            // $errorMessage = 'Card declined: ' . $e->getError()->message;
            // $truncatedError = strlen($errorMessage) > 255 ? substr($errorMessage, 0, 252) . '...' : $errorMessage;
            // $this->trackPaymentFunnel('failed', $type, $amount, null, $truncatedError);
            
            return back()->with('error', "Payment failed: ". $e->getError()->message);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Stripe API request issues
            \Log::error('Stripe invalid request exception', [
                'error_message' => $e->getMessage(),
                'stripe_code' => $e->getStripeCode(),
                'amount' => $request->amount,
                'type' => $request->type,
                'request_data' => $request->all()
            ]);
            
            return back()->with('error', "Payment failed: Invalid request - " . $e->getMessage());
        } catch (\Exception $e) {
            // Anything else
            \Log::error('Stripe payment exception', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'amount' => $request->amount,
                'type' => $request->type,
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Track payment failure - temporarily disabled due to string length issues
            // $amount = $request->input('amount', 0);
            // $type = $request->input('type', 'general');
            // $errorMessage = 'Payment processing error: ' . $e->getMessage();
            // $truncatedError = strlen($errorMessage) > 255 ? substr($errorMessage, 0, 252) . '...' : $errorMessage;
            // $this->trackPaymentFunnel('failed', $type, $amount, null, $truncatedError);
            
            report($e);
            return back()->with('error', "Payment failed: Payment could not be processed. Please check your card information and try again. Error: " . $e->getMessage());
        }
    }

    /**
     * Process a cart checkout with a single Authorize.Net transaction
     * Returns a simple payload used by CheckoutController to finish the flow.
     */
    public function processCartPayment(Request $request)
    {
        try {
            $cartData = $request->input('cart_data', []);
            $amount = (float)$request->input('amount', 0);

            if ($amount <= 0 || empty($cartData)) {
                return ['success' => false, 'message' => 'Invalid cart data or amount'];
            }

            // Resolve website by domain
            $url = url()->current();
            $domain = parse_url($url, PHP_URL_HOST);
            $website = Website::where('domain', $domain)->first();
            if (!$website) {
                return ['success' => false, 'message' => 'Website not found'];
            }

            $paymentGatewayService = new PaymentGatewayService();
            $merchantAuthentication = $paymentGatewayService->createAuthorizeNetAuth($website);
            if (!$merchantAuthentication) {
                return ['success' => false, 'message' => 'Payment gateway not configured'];
            }

            // Build card payload (match paymentPost behavior)
            $cardNumber = preg_replace('/[^0-9]/', '', trim($request->input('card_number')));
            $expirationInput = $request->input('expiration_date') ?? $request->input('date');
            $digits = preg_replace('/[^0-9]/', '', (string)$expirationInput);
            $expirationDate = null;
            if (strlen($digits) >= 4) {
                $expMonth = substr($digits, 0, 2);
                $expYear = substr($digits, 2);
                if (strlen($expYear) === 2) {
                    $expYear = '20' . $expYear;
                }
                if (strlen($expYear) === 4 && (int)$expMonth >= 1 && (int)$expMonth <= 12) {
                    $expirationDate = $expYear . '-' . $expMonth;
                }
            }
            if (!$expirationDate && !empty($expirationInput)) {
                $expirationDate = \Carbon\Carbon::parse($expirationInput)->format('Y-m');
            }
            $cvv = preg_replace('/[^0-9]/', '', trim($request->input('cvv')));
            if (empty($expirationDate)) {
                return ['success' => false, 'message' => 'Invalid expiration date'];
            }
            if (strlen($cvv) < 3 || strlen($cvv) > 4) {
                return ['success' => false, 'message' => 'Invalid security code'];
            }

            $creditCard = new AnetAPI\CreditCardType();
            $creditCard->setCardNumber($cardNumber);
            $creditCard->setExpirationDate($expirationDate);
            $creditCard->setCardCode($cvv);

            $paymentType = new AnetAPI\PaymentType();
            $paymentType->setCreditCard($creditCard);

            // Optional billing & customer data (match paymentPost behavior)
            if (!empty($request->zipcode) && !empty($request->state)) {
                $billTo = new AnetAPI\CustomerAddressType();
                if ($request->first_name) $billTo->setFirstName($request->first_name);
                if ($request->last_name) $billTo->setLastName($request->last_name);
                if ($request->zipcode) $billTo->setZip($request->zipcode);
                if ($request->state) $billTo->setState($request->state);
                if ($request->city) $billTo->setCity($request->city);
                if ($request->address) $billTo->setAddress($request->address);
                if ($request->country) $billTo->setCountry($request->country);
            }

            if (!empty($request->email) || !empty($request->first_name) || !empty($request->last_name)) {
                $customerData = new AnetAPI\CustomerDataType();
                $customerData->setType('individual');
                if (!empty($request->email)) {
                    $customerData->setEmail($request->email);
                }
                if (!empty($request->first_name) || !empty($request->last_name)) {
                    $customerData->setId(trim(($request->first_name ?? '') . ' ' . ($request->last_name ?? '')));
                }
            }

            $transactionRequest = new AnetAPI\TransactionRequestType();
            $transactionRequest->setTransactionType('authCaptureTransaction');
            $transactionRequest->setAmount(number_format($amount, 2, '.', ''));
            $transactionRequest->setPayment($paymentType);
            if (isset($billTo)) {
                $transactionRequest->setBillTo($billTo);
            }
            if (isset($customerData)) {
                $transactionRequest->setCustomer($customerData);
            }

            $clientIp = $request->getClientIp();
            if ($clientIp && filter_var($clientIp, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                $transactionRequest->setCustomerIP($clientIp);
            }

            $transactionReq = new AnetAPI\CreateTransactionRequest();
            $transactionReq->setMerchantAuthentication($merchantAuthentication);
            $transactionReq->setRefId('cart' . time());
            $transactionReq->setTransactionRequest($transactionRequest);

            $controller = new AnetController\CreateTransactionController($transactionReq);
            $environment = $paymentGatewayService->getAuthorizeNetEnvironment($website);
            $response = $controller->executeWithApiResponse($environment);

            if ($response && $response->getMessages()->getResultCode() === 'Ok') {
                $tresponse = $response->getTransactionResponse();
                if ($tresponse && $tresponse->getResponseCode() == "1") {
                    return [
                        'success' => true,
                        'transaction_id' => $tresponse->getTransId(),
                        'auth_code' => $tresponse->getAuthCode(),
                    ];
                }
            }

            $errorResponse = $response ? $response->getTransactionResponse() : null;
            $errors = $errorResponse ? $errorResponse->getErrors() : [];
            $error = (!empty($errors) && isset($errors[0])) ? $errors[0]->getErrorText() : 'Payment failed';
            return ['success' => false, 'message' => $error];

        } catch (\Exception $e) {
            \Log::error('Authorize.Net cart payment error', ['message' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Authorize.Net cart payment error'];
        }
    }

    public function setting()
    {
        $data = PaymentSetting::first();

        return view('admin.setting.payment', compact('data'));
    }

    public function update(Request $request){
        // dd($request->all());
        $update = PaymentSetting::first();
        $update->app_id = $request->app_id;
        $update->transaction_id = $request->transaction_id;
        $update->fee = $request->fee;
        $update->update();

        return back();
    }

    /**
     * Send invoice email after transaction
     */
    private function sendInvoiceEmail($transaction, $website)
    {
        try {
            if ($website) {
                \App\Services\WebsiteMailService::applyForWebsite($website);
            }
            
            // Send to customer's email
            if ($transaction->email !== 'admin@admin') {
                Mail::to($transaction->email)->send(new TransactionInvoice($transaction, $website));
            }
            
            // Also send to website owner emails that have transaction preference enabled
            if ($website) {
                $websiteEmails = $website->getTransactionEmails();
                foreach ($websiteEmails as $email) {
                    if ($email !== $transaction->email && $email !== 'admin@admin') {  // Don't send duplicate if customer email is in list, skip admin@admin
                        Mail::to($email)->send(new TransactionInvoice($transaction, $website));
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send transaction invoice', [
                'transaction_id' => $transaction->transaction_id,
                'email' => $transaction->email,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle post-transaction operations (email, logging, etc.)
     */
    private function afterTransactionSaved($transaction, $website)
    {
        // Send invoice email
        $this->sendInvoiceEmail($transaction, $website);
        
        // Log successful transaction
        \Log::info('Transaction completed and email sent', [
            'transaction_id' => $transaction->transaction_id,
            'email' => $transaction->email,
            'amount' => $transaction->amount,
            'type' => $transaction->type ?? 'unknown'
        ]);
    }

    /**
     * Set common transaction fields including IP address
     */
    private function setTransactionFields($transaction, $request, $transactionId, $donationOrAmount, $websiteId, $type)
    {
        $transaction->amount = is_object($donationOrAmount) ? $donationOrAmount->amount : $donationOrAmount;
        $transaction->type = $type;
        $transaction->website_id = $websiteId;
        $transaction->transaction_id = $transactionId;
        $transaction->name = $request->first_name;
        $transaction->last_name = $request->last_name;
        $transaction->email = $request->email;
        $transaction->address = $request->address ?? null;
        $transaction->apartment = $request->apartment ?? null;
        $transaction->city = $request->city ?? null;
        $transaction->state = $request->state ?? null;
        $transaction->zip = $request->zipcode ?? null;
        $transaction->phone = $request->phone ?? null;
        $transaction->name_on_card = $request->name_on_card ?? null;
        $transaction->country = $request->country ?? null;
        $transaction->ip_address = $request->ip();
        $transaction->fee = 0;
        $transaction->fee_paid = 1;
        
        return $transaction;
    }

    /**
     * Track payment funnel events
     */
    protected function trackPaymentFunnel($event, $type, $amount, $transactionId = null, $errorMessage = null, $userId = null, $paymentMethod = null)
    {
        try {
            \Log::info('Payment funnel tracking initiated', [
                'event' => $event,
                'type' => $type,
                'amount' => $amount,
                'transaction_id' => $transactionId,
                'payment_method' => $paymentMethod
            ]);
            
            $funnelService = new PaymentFunnelService();
            
            // Determine form type based on type parameter
            $formType = $this->mapTypeToFormType($type);
            
            // Auto-detect payment method if not provided
            if (!$paymentMethod) {
                $paymentMethod = $this->detectPaymentMethod();
            }
            
            \Log::info('Payment funnel tracking details', [
                'form_type' => $formType,
                'payment_method' => $paymentMethod,
                'event' => $event
            ]);
            
            if ($event === 'completed') {
                $result = $funnelService->trackPaymentCompleted(
                    $formType,
                    $amount,
                    $paymentMethod,
                    $transactionId,
                    $userId
                );
                \Log::info('Payment completion tracked successfully', ['result' => $result ? $result->id : 'false']);
            } elseif ($event === 'failed') {
                $result = $funnelService->trackPaymentFailed(
                    $formType,
                    $amount,
                    $paymentMethod,
                    $errorMessage,
                    $userId
                );
                \Log::info('Payment failure tracked successfully', ['result' => $result ? $result->id : 'false']);
            }
        } catch (\Exception $e) {
            // Log error but don't fail the payment process
            \Log::error('Payment funnel tracking error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'event' => $event,
                'type' => $type,
                'amount' => $amount
            ]);
        }
    }

    /**
     * Auto-detect payment method from request context
     */
    protected function detectPaymentMethod()
    {
        $request = request();
        
        // Check if it's a Stripe payment (has stripeToken)
        if ($request->has('stripeToken')) {
            return 'stripe';
        }
        
        // Check if it's crypto payment (future implementation)
        if ($request->has('cryptoWallet') || $request->has('blockchainTx')) {
            return 'crypto';
        }
        
        // Default to Authorize.Net
        return 'authorize_net';
    }

    /**
     * Map payment type to form type for funnel tracking
     */
    protected function mapTypeToFormType($type)
    {
        switch ($type) {
            case 'student':
                return 'student';
            case 'donation':
            case 'general':
                return 'general';
            case 'ticket':
                return 'ticket';
            case 'auction':
                return 'auction';
            case 'investment':
                return 'investment';
            default:
                return 'general';
        }
    }
}
