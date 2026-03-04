<?php

namespace App\Http\Controllers;

use App\Models\CryptoPayment;
use App\Models\Donation;
use App\Models\EventTicket;
use App\Models\AuctionBid;
use App\Models\Investment;
use App\Models\Website;
use App\Services\CoinbaseCommerceService;
use App\Services\PaymentFunnelService;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CoinbaseController extends Controller
{
    protected $coinbaseService;
    protected $funnelService;
    protected $notificationService;
    protected $website;

    public function __construct(CoinbaseCommerceService $coinbaseService, PaymentFunnelService $funnelService)
    {
        $this->coinbaseService = $coinbaseService;
        $this->funnelService = $funnelService;
        $this->notificationService = new PushNotificationService();
        $this->website = $this->getWebsite();
    }
    
    /**
     * Get the current website
     */
    protected function getWebsite()
    {
        $host = request()->getHost();
        
        // Try exact domain match
        $website = Website::where('domain', $host)->first();
        
        // Try without www
        if (!$website && str_starts_with($host, 'www.')) {
            $website = Website::where('domain', substr($host, 4))->first();
        }
        
        // Try with www
        if (!$website && !str_starts_with($host, 'www.')) {
            $website = Website::where('domain', 'www.' . $host)->first();
        }
        
        // Fallback
        if (!$website && session('website_id')) {
            $website = Website::find(session('website_id'));
        }
        
        if (!$website) {
            $website = Website::first();
        }
        
        return $website;
    }
    
    /**
     * Get Coinbase credentials for the current website
     */
    protected function getCoinbaseCredentials()
    {
        if (!$this->website) {
            return null;
        }
        
        $paymentSettings = $this->website->paymentSettings;
        
        if ($paymentSettings && $paymentSettings->isCoinbaseConfigured()) {
            return $paymentSettings->getCoinbaseConfig();
        }
        
        // Fallback to global config
        return [
            'api_key' => config('coinbase.api_key'),
            'webhook_secret' => config('coinbase.webhook_secret'),
        ];
    }

    /**
     * Show crypto payment page
     */
    public function showPaymentPage(Request $request)
    {
        $amount = $request->amount;
        $type = $request->type; // donation, ticket, auction, investment
        $referenceId = $request->donation_id ?? $request->reference_id;
        
        // Get website settings
        $setting = \App\Models\Setting::first();
        
        return view('crypto-payment', compact('amount', 'type', 'referenceId', 'setting'));
    }

    /**
     * Create Coinbase charge
     */
    public function createCharge(Request $request)
    {
        // Get website-specific Coinbase credentials
        $credentials = $this->getCoinbaseCredentials();
        
        if (!$credentials || !$credentials['api_key']) {
            return response()->json([
                'success' => false,
                'error' => 'Coinbase Commerce is not configured for this website'
            ], 400);
        }
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'type' => 'required|in:donation,ticket,auction,investment',
            'reference_id' => 'required',
            'currency' => 'nullable|string|in:USD,EUR,GBP',
            'website_id' => 'nullable|exists:websites,id',
        ]);

        try {
            // Get reference details
            $referenceDetails = $this->getReferenceDetails($validated['type'], $validated['reference_id']);
            
            if (!$referenceDetails) {
                Log::warning('Coinbase createCharge: reference not found', [
                    'type' => $validated['type'],
                    'reference_id' => $validated['reference_id'],
                ]);

                return response()->json([
                    'success' => false,
                    'error' => 'Invalid reference ID for provided type',
                    'type' => $validated['type'],
                    'reference_id' => $validated['reference_id'],
                ], 400);
            }
            
            // Set website-specific credentials on the service
            $this->coinbaseService->setCredentials($credentials['api_key'], $credentials['webhook_secret']);

            // Log masked API key for debugging (do not log full key)
            try {
                $apiKey = $credentials['api_key'] ?? null;
                if ($apiKey) {
                    $masked = substr($apiKey, 0, 4) . str_repeat('*', max(0, strlen($apiKey) - 8)) . substr($apiKey, -4);
                } else {
                    $masked = 'none';
                }
                Log::info('Coinbase createCharge: using api key', ['api_key_masked' => $masked, 'website_id' => $validated['website_id'] ?? null]);
            } catch (\Throwable $e) {
                // ignore logging errors
            }

            // Track funnel: payment_initiated (use service helper)
            $this->funnelService->trackPaymentInitiated(
                $validated['type'],
                $validated['amount'],
                'coinbase',
                $validated['form_data'] ?? [],
                auth()->id() ?? null
            );

            // Create Coinbase charge
            $chargeData = [
                'name' => $referenceDetails['name'],
                'description' => $referenceDetails['description'],
                'amount' => number_format($validated['amount'], 2, '.', ''),
                'currency' => $validated['currency'] ?? 'USD',
                'metadata' => [
                    'type' => $validated['type'],
                    'reference_id' => $validated['reference_id'],
                    'user_id' => auth()->id(),
                    'website_id' => $validated['website_id'] ?? null,
                    'session_id' => session()->getId(),
                ],
            ];

            $result = $this->coinbaseService->createCharge($chargeData);

            if (!$result['success']) {
                // Track failure
                $this->funnelService->trackPaymentFailed(
                    $validated['type'],
                    $validated['amount'],
                    'coinbase',
                    $result['error'],
                    auth()->id() ?? null
                );

                return response()->json([
                    'success' => false,
                    'error' => $result['error'],
                ], 400);
            }

            $charge = $result['data'];

            // Save to database
            $cryptoPayment = CryptoPayment::create([
                'charge_code' => $charge['code'],
                'charge_id' => $charge['id'],
                'payment_type' => $validated['type'],
                'reference_id' => $validated['reference_id'],
                'user_id' => auth()->id(),
                'website_id' => $validated['website_id'] ?? null,
                'amount' => $validated['amount'],
                'currency' => $validated['currency'] ?? 'USD',
                'status' => 'pending',
                'hosted_url' => $charge['hosted_url'],
                'charge_data' => $charge,
                'session_id' => session()->getId(),
            ]);

            // Note: payment_processing is not a tracked funnel step in PaymentFunnelEvent.
            // We already tracked payment_initiated above; no additional funnel event is required here.

            return response()->json([
                'success' => true,
                'charge_code' => $charge['code'],
                'hosted_url' => $charge['hosted_url'],
                'checkout_url' => $this->coinbaseService->getCheckoutUrl($charge['code']),
            ]);

        } catch (\Exception $e) {
            Log::error('Coinbase Charge Creation Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to create crypto payment charge',
            ], 500);
        }
    }

    /**
     * Coinbase webhook handler
     */
    public function webhook(Request $request)
    {
        try {
            $payload = $request->getContent();
            $signature = $request->header('X-CC-Webhook-Signature');
            
            $data = json_decode($payload, true);
            $event = $data['event'];

            // Find the crypto payment to get the website
            $cryptoPayment = CryptoPayment::where('charge_code', $event['data']['code'])->first();

            if (!$cryptoPayment) {
                Log::warning('Crypto payment not found', ['charge_code' => $event['data']['code']]);
                return response()->json(['error' => 'Payment not found'], 404);
            }
            
            // Get website-specific credentials for verification
            $website = Website::find($cryptoPayment->website_id);
            if ($website && $website->paymentSettings && $website->paymentSettings->isCoinbaseConfigured()) {
                $config = $website->paymentSettings->getCoinbaseConfig();
                $this->coinbaseService->setCredentials($config['api_key'], $config['webhook_secret']);
            }

            // Verify webhook signature with website-specific secret
            if (!$this->coinbaseService->verifyWebhookSignature($payload, $signature)) {
                Log::warning('Invalid Coinbase webhook signature');
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            Log::info('Coinbase Webhook Received', ['event' => $event]);

            // Handle different event types
            switch ($event['type']) {
                case 'charge:confirmed':
                    $this->handleConfirmed($cryptoPayment, $event['data']);
                    break;
                    
                case 'charge:failed':
                    $this->handleFailed($cryptoPayment, $event['data']);
                    break;
                    
                case 'charge:delayed':
                    $this->handleDelayed($cryptoPayment, $event['data']);
                    break;
                    
                case 'charge:pending':
                    $this->handlePending($cryptoPayment, $event['data']);
                    break;
                    
                case 'charge:resolved':
                    $this->handleResolved($cryptoPayment, $event['data']);
                    break;
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Coinbase Webhook Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Handle confirmed payment
     */
    protected function handleConfirmed($cryptoPayment, $chargeData)
    {
        DB::transaction(function () use ($cryptoPayment, $chargeData) {
            $cryptoPayment->update([
                'status' => 'completed',
                'completed_at' => now(),
                'charge_data' => $chargeData,
            ]);

            // Update the reference model (Donation, Ticket, etc.)
            $this->updateReferenceModel($cryptoPayment);

            // Track funnel: payment_completed
            $this->funnelService->trackPaymentCompleted(
                $cryptoPayment->payment_type,
                $cryptoPayment->amount,
                'coinbase',
                $cryptoPayment->charge_code,
                $cryptoPayment->user_id ?? null
            );

            // Send notification if this is a donation
            try {
                if ($cryptoPayment->payment_type === 'donation') {
                    $donation = Donation::find($cryptoPayment->reference_id);
                    if ($donation && $donation->user_id) {
                        $donorName = trim($donation->first_name . ' ' . $donation->last_name);
                        if (empty($donorName)) {
                            $donorName = 'Anonymous Donor';
                        }
                        
                        $this->notificationService->sendDonationNotification(
                            $donation->user_id,
                            $donation->amount,
                            $donorName,
                            $donation->id
                        );
                    }
                }
            } catch (\Exception $e) {
                Log::error('Push notification error in Coinbase payment: ' . $e->getMessage());
            }

            Log::info('Crypto payment confirmed', ['payment_id' => $cryptoPayment->id]);
        });
    }

    /**
     * Handle failed payment
     */
    protected function handleFailed($cryptoPayment, $chargeData)
    {
        $cryptoPayment->update([
            'status' => 'failed',
            'charge_data' => $chargeData,
        ]);

        // Track failure
        $this->funnelService->trackPaymentFailed(
            $cryptoPayment->payment_type,
            $cryptoPayment->amount,
            'coinbase',
            json_encode($chargeData),
            $cryptoPayment->user_id ?? null
        );

        Log::warning('Crypto payment failed', ['payment_id' => $cryptoPayment->id]);
    }

    /**
     * Handle delayed payment
     */
    protected function handleDelayed($cryptoPayment, $chargeData)
    {
        $cryptoPayment->update([
            'status' => 'delayed',
            'charge_data' => $chargeData,
        ]);

        Log::info('Crypto payment delayed', ['payment_id' => $cryptoPayment->id]);
    }

    /**
     * Handle pending payment
     */
    protected function handlePending($cryptoPayment, $chargeData)
    {
        $cryptoPayment->update([
            'status' => 'pending',
            'charge_data' => $chargeData,
        ]);

        Log::info('Crypto payment pending', ['payment_id' => $cryptoPayment->id]);
    }

    /**
     * Handle resolved payment
     */
    protected function handleResolved($cryptoPayment, $chargeData)
    {
        $cryptoPayment->update([
            'status' => 'resolved',
            'charge_data' => $chargeData,
        ]);

        Log::info('Crypto payment resolved', ['payment_id' => $cryptoPayment->id]);
    }

    /**
     * Get reference details based on type
     */
    protected function getReferenceDetails($type, $referenceId)
    {
        switch ($type) {
            case 'donation':
                $donation = Donation::find($referenceId);
                return $donation ? [
                    'name' => 'Donation #' . $donation->id,
                    'description' => 'Donation to ' . ($donation->campaign->name ?? 'Campaign'),
                ] : null;
                
            case 'ticket':
                $ticket = EventTicket::find($referenceId);
                return $ticket ? [
                    'name' => 'Event Ticket #' . $ticket->id,
                    'description' => 'Ticket for ' . ($ticket->event->name ?? 'Event'),
                ] : null;
                
            case 'auction':
                $bid = AuctionBid::find($referenceId);
                return $bid ? [
                    'name' => 'Auction Bid #' . $bid->id,
                    'description' => 'Bid on ' . ($bid->auction->item_name ?? 'Auction Item'),
                ] : null;
                
            case 'investment':
                $investment = Investment::find($referenceId);
                return $investment ? [
                    'name' => 'Investment #' . $investment->id,
                    'description' => 'Investment in ' . ($investment->property->name ?? 'Property'),
                ] : null;
                
            default:
                return null;
        }
    }

    /**
     * Update reference model after payment
     */
    protected function updateReferenceModel($cryptoPayment)
    {
        switch ($cryptoPayment->payment_type) {
            case 'donation':
                $donation = Donation::find($cryptoPayment->reference_id);
                if ($donation) {
                    $donation->update(['status' => 'completed', 'payment_method' => 'coinbase']);
                }
                break;
                
            case 'ticket':
                $ticket = EventTicket::find($cryptoPayment->reference_id);
                if ($ticket) {
                    $ticket->update(['payment_status' => 'paid', 'payment_method' => 'coinbase']);
                }
                break;
                
            case 'auction':
                $bid = AuctionBid::find($cryptoPayment->reference_id);
                if ($bid) {
                    $bid->update(['payment_status' => 'paid', 'payment_method' => 'coinbase']);
                }
                break;
                
            case 'investment':
                $investment = Investment::find($cryptoPayment->reference_id);
                if ($investment) {
                    $investment->update(['status' => 'active', 'payment_method' => 'coinbase']);
                }
                break;
        }
    }

    /**
     * Check payment status
     */
    public function checkStatus($chargeCode)
    {
        $cryptoPayment = CryptoPayment::where('charge_code', $chargeCode)->first();

        if (!$cryptoPayment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        // Get latest status from Coinbase
        $result = $this->coinbaseService->getCharge($chargeCode);

        if ($result['success']) {
            $cryptoPayment->update(['charge_data' => $result['data']]);
        }

        return response()->json([
            'status' => $cryptoPayment->status,
            'charge_code' => $cryptoPayment->charge_code,
            'amount' => $cryptoPayment->amount,
            'currency' => $cryptoPayment->currency,
        ]);
    }
}
