<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CoinbaseCommerceService
{
    protected $apiKey;
    protected $apiUrl;
    protected $webhookSecret;

    public function __construct($apiKey = null, $webhookSecret = null)
    {
        $this->apiKey = $apiKey ?? config('coinbase.api_key');
        $this->apiUrl = config('coinbase.api_url');
        $this->webhookSecret = $webhookSecret ?? config('coinbase.webhook_secret');
    }
    
    /**
     * Set API credentials dynamically (for per-website configuration)
     */
    public function setCredentials($apiKey, $webhookSecret = null)
    {
        $this->apiKey = $apiKey;
        if ($webhookSecret) {
            $this->webhookSecret = $webhookSecret;
        }
    }

    /**
     * Create a charge for crypto payment
     */
    public function createCharge($data)
    {
        try {
            $client = Http::withOptions([
                'verify' => config('coinbase.verify_ssl', true),
            ])->withHeaders([
                'X-CC-Api-Key' => $this->apiKey,
                'X-CC-Version' => '2018-03-22',
                'Content-Type' => 'application/json',
            ]);

            $response = $client->post("{$this->apiUrl}/charges", [
                'name' => $data['name'] ?? 'Payment',
                'description' => $data['description'] ?? 'Payment description',
                'pricing_type' => 'fixed_price',
                'local_price' => [
                    'amount' => $data['amount'],
                    'currency' => $data['currency'] ?? 'USD',
                ],
                'metadata' => $data['metadata'] ?? [],
                'redirect_url' => config('coinbase.redirect_url'),
                'cancel_url' => config('coinbase.cancel_url'),
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()['data'],
                ];
            }

            Log::error('Coinbase Commerce API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => $response->json()['error']['message'] ?? 'Unknown error',
            ];

        } catch (\Exception $e) {
            Log::error('Coinbase Commerce Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get charge details
     */
    public function getCharge($chargeCode)
    {
        try {
            $client = Http::withOptions([
                'verify' => config('coinbase.verify_ssl', true),
            ])->withHeaders([
                'X-CC-Api-Key' => $this->apiKey,
                'X-CC-Version' => '2018-03-22',
            ]);

            $response = $client->get("{$this->apiUrl}/charges/{$chargeCode}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()['data'],
                ];
            }

            return [
                'success' => false,
                'error' => 'Charge not found',
            ];

        } catch (\Exception $e) {
            Log::error('Coinbase Get Charge Exception', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature($payload, $signature)
    {
        $computedSignature = hash_hmac('sha256', $payload, $this->webhookSecret);
        return hash_equals($computedSignature, $signature);
    }

    /**
     * Get hosted checkout URL
     */
    public function getCheckoutUrl($chargeCode)
    {
        return "https://commerce.coinbase.com/charges/{$chargeCode}";
    }
}
