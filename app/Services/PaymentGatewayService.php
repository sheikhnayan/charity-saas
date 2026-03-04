<?php

namespace App\Services;

use App\Models\Website;
use App\Models\WebsitePaymentSetting;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymentGatewayService
{
    /**
     * Get payment configuration for a website by domain
     */
    public function getPaymentConfigByDomain(string $domain): array
    {
        try {
            $website = Website::where('domain', $domain)->first();
            
            if (!$website) {
                Log::warning("Website not found for domain: {$domain}");
                return $this->getFallbackConfig();
            }
            
            return $this->getPaymentConfigForWebsite($website);
        } catch (Exception $e) {
            Log::error("Error getting payment config for domain {$domain}: " . $e->getMessage());
            return $this->getFallbackConfig();
        }
    }

    /**
     * Get payment configuration for a specific website
     */
    public function getPaymentConfigForWebsite(Website $website): array
    {
        try {
            $config = $website->getPaymentConfig();
            $paymentMethod = $website->getPaymentMethod();
            
            return [
                'payment_method' => $paymentMethod,
                'config' => $config,
                'website_id' => $website->id,
                'is_sandbox' => $this->isSandboxMode($website)
            ];
        } catch (Exception $e) {
            Log::error("Error getting payment config for website {$website->id}: " . $e->getMessage());
            return $this->getFallbackConfig();
        }
    }

    /**
     * Initialize Stripe with website-specific credentials
     */
    public function initializeStripe(Website $website): bool
    {
        try {
            $config = $website->getPaymentConfig();
            
            if (empty($config['secret_key'])) {
                Log::warning("Stripe secret key not found for website {$website->id}");
                return false;
            }
            
            \Stripe\Stripe::setApiKey($config['secret_key']);
            return true;
        } catch (Exception $e) {
            Log::error("Error initializing Stripe for website {$website->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get Authorize.net environment based on website settings
     */
    public function getAuthorizeNetEnvironment(Website $website): string
    {
        $paymentSettings = $website->paymentSettings;
        
        if ($paymentSettings && isset($paymentSettings->authorize_sandbox)) {
            return $paymentSettings->authorize_sandbox 
                ? \net\authorize\api\constants\ANetEnvironment::SANDBOX 
                : \net\authorize\api\constants\ANetEnvironment::PRODUCTION;
        }
        
        // Default to sandbox for safety
        return \net\authorize\api\constants\ANetEnvironment::SANDBOX;
    }

    /**
     * Create Authorize.net merchant authentication for a website
     */
    public function createAuthorizeNetAuth(Website $website): ?\net\authorize\api\contract\v1\MerchantAuthenticationType
    {
        try {
            $config = $website->getPaymentConfig();
            
            if (empty($config['login_id']) || empty($config['transaction_key'])) {
                Log::warning("Authorize.net credentials not found for website {$website->id}");
                return null;
            }
            
            $merchantAuthentication = new \net\authorize\api\contract\v1\MerchantAuthenticationType();
            $merchantAuthentication->setName($config['login_id']);
            $merchantAuthentication->setTransactionKey($config['transaction_key']);
            
            return $merchantAuthentication;
        } catch (Exception $e) {
            Log::error("Error creating Authorize.net auth for website {$website->id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Validate payment configuration for a website
     */
    public function validatePaymentConfig(Website $website): array
    {
        $errors = [];
        $paymentMethod = $website->getPaymentMethod();
        $config = $website->getPaymentConfig();
        
        if ($paymentMethod === 'stripe') {
            if (empty($config['publishable_key'])) {
                $errors[] = 'Stripe publishable key is required';
            }
            if (empty($config['secret_key'])) {
                $errors[] = 'Stripe secret key is required';
            }
        } elseif ($paymentMethod === 'authorize') {
            if (empty($config['login_id'])) {
                $errors[] = 'Authorize.net login ID is required';
            }
            if (empty($config['transaction_key'])) {
                $errors[] = 'Authorize.net transaction key is required';
            }
        } else {
            $errors[] = 'Invalid payment method specified';
        }
        
        return $errors;
    }

    /**
     * Check if website is in sandbox mode
     */
    private function isSandboxMode(Website $website): bool
    {
        $paymentSettings = $website->paymentSettings;
        
        if ($paymentSettings && $paymentSettings->payment_method === 'authorize') {
            return $paymentSettings->authorize_sandbox ?? true;
        }
        
        // For Stripe, we can determine sandbox mode by API key prefix
        if ($paymentSettings && $paymentSettings->payment_method === 'stripe') {
            $secretKey = $paymentSettings->stripe_secret_key ?? '';
            return strpos($secretKey, 'sk_test_') === 0;
        }
        
        return true; // Default to sandbox for safety
    }

    /**
     * Get fallback configuration (uses environment variables)
     */
    private function getFallbackConfig(): array
    {
        return [
            'payment_method' => env('DEFAULT_PAYMENT_METHOD', 'authorize'),
            'config' => [
                'login_id' => env('AUTHORIZENET_API_LOGIN_ID'),
                'transaction_key' => env('AUTHORIZENET_TRANSACTION_KEY'),
                'publishable_key' => env('STRIPE_KEY'),
                'secret_key' => env('STRIPE_SECRET'),
            ],
            'website_id' => null,
            'is_sandbox' => true
        ];
    }

    /**
     * Test payment gateway connection
     */
    public function testPaymentGateway(Website $website): array
    {
        $result = [
            'success' => false,
            'message' => '',
            'details' => []
        ];
        
        try {
            $paymentMethod = $website->getPaymentMethod();
            $config = $website->getPaymentConfig();
            
            if ($paymentMethod === 'stripe') {
                $result = $this->testStripeConnection($config);
            } elseif ($paymentMethod === 'authorize') {
                $result = $this->testAuthorizeNetConnection($website, $config);
            } else {
                $result['message'] = 'Unknown payment method';
            }
        } catch (Exception $e) {
            $result['message'] = 'Error testing payment gateway: ' . $e->getMessage();
        }
        
        return $result;
    }

    /**
     * Test Stripe connection
     */
    private function testStripeConnection(array $config): array
    {
        try {
            if (empty($config['secret_key'])) {
                return ['success' => false, 'message' => 'Stripe secret key is missing'];
            }
            
            \Stripe\Stripe::setApiKey($config['secret_key']);
            
            // Try to retrieve account information
            $account = \Stripe\Account::retrieve();
            
            return [
                'success' => true,
                'message' => 'Stripe connection successful',
                'details' => [
                    'account_id' => $account->id,
                    'country' => $account->country,
                    'currency' => $account->default_currency
                ]
            ];
        } catch (\Stripe\Exception\AuthenticationException $e) {
            return ['success' => false, 'message' => 'Stripe authentication failed: Invalid API key'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Stripe connection failed: ' . $e->getMessage()];
        }
    }

    /**
     * Test Authorize.net connection
     */
    private function testAuthorizeNetConnection(Website $website, array $config): array
    {
        try {
            if (empty($config['login_id']) || empty($config['transaction_key'])) {
                return ['success' => false, 'message' => 'Authorize.net credentials are missing'];
            }
            
            $merchantAuth = $this->createAuthorizeNetAuth($website);
            if (!$merchantAuth) {
                return ['success' => false, 'message' => 'Failed to create merchant authentication'];
            }
            
            // Create a test request to validate credentials
            $request = new \net\authorize\api\contract\v1\GetMerchantDetailsRequest();
            $request->setMerchantAuthentication($merchantAuth);
            
            $controller = new \net\authorize\api\controller\GetMerchantDetailsController($request);
            $response = $controller->executeWithApiResponse($this->getAuthorizeNetEnvironment($website));
            
            if ($response && $response->getMessages()->getResultCode() == "Ok") {
                return [
                    'success' => true,
                    'message' => 'Authorize.net connection successful',
                    'details' => [
                        'environment' => $this->isSandboxMode($website) ? 'sandbox' : 'production'
                    ]
                ];
            } else {
                $errorMessages = [];
                if ($response && $response->getMessages() && $response->getMessages()->getMessage()) {
                    foreach ($response->getMessages()->getMessage() as $message) {
                        $errorMessages[] = $message->getText();
                    }
                }
                return [
                    'success' => false, 
                    'message' => 'Authorize.net authentication failed: ' . implode(', ', $errorMessages)
                ];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Authorize.net connection failed: ' . $e->getMessage()];
        }
    }

    /**
     * Get supported payment methods for a website
     */
    public function getSupportedPaymentMethods(Website $website): array
    {
        $methods = [];
        
        // Check if Authorize.Net is configured
        $authorizeNetConfig = $this->getAuthorizeNetConfig($website);
        if (!empty($authorizeNetConfig)) {
            $methods[] = [
                'method' => 'authorize_net',
                'name' => 'Credit/Debit Card (Authorize.Net)',
                'supported_types' => ['card'],
                'enabled' => true
            ];
        }

        // Check if Stripe is configured
        $stripeConfig = $this->getStripeConfig($website);
        if (!empty($stripeConfig)) {
            $methods[] = [
                'method' => 'stripe',
                'name' => 'Credit/Debit Card (Stripe)',
                'supported_types' => ['card', 'apple_pay', 'google_pay'],
                'enabled' => true
            ];
        }

        // Future: Crypto wallet support
        $cryptoConfig = $this->getCryptoWalletConfig($website);
        if (!empty($cryptoConfig)) {
            $methods[] = [
                'method' => 'crypto',
                'name' => 'Cryptocurrency Wallet',
                'supported_types' => ['bitcoin', 'ethereum', 'usdc', 'other'],
                'enabled' => $cryptoConfig['enabled'] ?? false
            ];
        }

        return $methods;
    }

    /**
     * Get crypto wallet configuration (Future implementation)
     */
    public function getCryptoWalletConfig(Website $website): array
    {
        // This will be implemented when crypto payment is added
        // For now, return empty to indicate crypto is not configured
        
        // Future structure might look like:
        // $cryptoSetting = WebsitePaymentSetting::where('website_id', $website->id)
        //     ->where('gateway', 'crypto')
        //     ->first();
        
        // if ($cryptoSetting && $cryptoSetting->is_active) {
        //     return [
        //         'enabled' => true,
        //         'supported_currencies' => $cryptoSetting->config['supported_currencies'] ?? ['BTC', 'ETH', 'USDC'],
        //         'wallet_addresses' => $cryptoSetting->config['wallet_addresses'] ?? [],
        //         'network' => $cryptoSetting->config['network'] ?? 'mainnet',
        //         'api_key' => $cryptoSetting->config['api_key'] ?? null,
        //     ];
        // }
        
        return [];
    }

    /**
     * Validate crypto transaction (Future implementation)
     */
    public function validateCryptoTransaction(string $txHash, string $currency, float $expectedAmount): array
    {
        // Future implementation for blockchain transaction validation
        // This would integrate with blockchain APIs to verify transactions
        
        return [
            'success' => false,
            'message' => 'Crypto validation not yet implemented',
            'transaction_hash' => $txHash,
            'status' => 'pending'
        ];
    }

    /**
     * Process crypto payment (Future implementation)
     */
    public function processCryptoPayment(array $paymentData): array
    {
        // Future implementation for crypto payment processing
        // This would handle crypto payment flows
        
        return [
            'success' => false,
            'message' => 'Crypto payments not yet implemented',
            'requires_implementation' => true
        ];
    }
}