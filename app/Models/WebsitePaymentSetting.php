<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class WebsitePaymentSetting extends Model
{
    protected $fillable = [
        'website_id',
        'payment_method',
        'fee',
        'stripe_publishable_key',
        'stripe_secret_key',
        'stripe_webhook_secret',
        'authorize_login_id',
        'authorize_transaction_key',
        'authorize_sandbox',
        'coinbase_enabled',
        'coinbase_api_key',
        'coinbase_webhook_secret',
        'tipping_enabled',
        'is_active',
        'settings'
    ];

    protected $casts = [
        'stripe_publishable_key' => 'encrypted',
        'stripe_secret_key' => 'encrypted',
        'stripe_webhook_secret' => 'encrypted',
        'authorize_login_id' => 'encrypted',
        'authorize_transaction_key' => 'encrypted',
        'authorize_sandbox' => 'boolean',
        'coinbase_enabled' => 'boolean',
        'coinbase_api_key' => 'encrypted',
        'coinbase_webhook_secret' => 'encrypted',
        'tipping_enabled' => 'boolean',
        'is_active' => 'boolean',
        'settings' => 'array'
    ];

    /**
     * Get the website that owns this payment setting
     */
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * Check if Stripe is configured and active
     */
    public function isStripeConfigured(): bool
    {
        return $this->payment_method === 'stripe' 
            && !empty($this->stripe_publishable_key) 
            && !empty($this->stripe_secret_key)
            && $this->is_active;
    }

    /**
     * Check if Authorize.net is configured and active
     */
    public function isAuthorizeConfigured(): bool
    {
        return $this->payment_method === 'authorize' 
            && !empty($this->authorize_login_id) 
            && !empty($this->authorize_transaction_key)
            && $this->is_active;
    }

    /**
     * Check if Coinbase Commerce is configured and active
     * Note: Coinbase is now optional and can work alongside primary gateway
     */
    public function isCoinbaseConfigured(): bool
    {
        return $this->coinbase_enabled 
            && !empty($this->coinbase_api_key)
            && $this->is_active;
    }

    /**
     * Get Stripe configuration array
     */
    public function getStripeConfig(): array
    {
        return [
            'publishable_key' => $this->stripe_publishable_key,
            'secret_key' => $this->stripe_secret_key,
            'webhook_secret' => $this->stripe_webhook_secret,
        ];
    }

    /**
     * Get Authorize.net configuration array
     */
    public function getAuthorizeConfig(): array
    {
        return [
            'login_id' => $this->authorize_login_id,
            'transaction_key' => $this->authorize_transaction_key,
            'sandbox' => $this->authorize_sandbox,
        ];
    }

    /**
     * Get Coinbase Commerce configuration array
     */
    public function getCoinbaseConfig(): array
    {
        return [
            'api_key' => $this->coinbase_api_key,
            'webhook_secret' => $this->coinbase_webhook_secret,
        ];
    }

    /**
     * Get the appropriate payment configuration based on payment method
     */
    public function getPaymentConfig(): array
    {
        if ($this->payment_method === 'stripe') {
            return $this->getStripeConfig();
        } elseif ($this->payment_method === 'coinbase') {
            return $this->getCoinbaseConfig();
        } else {
            return $this->getAuthorizeConfig();
        }
    }

    /**
     * Get the processing fee for this website
     * Falls back to global fee if not set
     */
    public function getProcessingFee(): float
    {
        if ($this->fee !== null) {
            return (float) $this->fee;
        }
        
        // Fallback to global payment settings
        $globalSettings = \App\Models\PaymentSetting::first();
        return $globalSettings ? (float) $globalSettings->fee : 2.9;
    }
}

