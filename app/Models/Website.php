<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = [
        'name',
        'domain',
        'user_id',
        'type',
        'status',
        'share_price',
        'min_investment',
        'investment_tiers',
        'investment_title',
        'sticky_footer_button_bg',
        'sticky_footer_button_text',
        'sticky_footer_text_color',
        'sticky_footer_bg_color',
        'asset_type',
        'offering_type',
        'asset_type_label',
        'offering_type_label',
        'additional_information',
        'invest_page_title',
        'invest_amount_title',
        'share_price_label',
        'minimum_investment_label',
        'custom_sticky_button_text',
        'google_analytics_id',
        'contact_emails',
        // Property details theming
        'property_details_bg_color',
        'property_details_text_color',
        'property_details_muted_color',
        'property_details_heading_color',
        'property_details_price_color',
        'property_details_accent_color'
    ];

    protected $casts = [
        'contact_emails' => 'array',
    ];

    /**
     * Get emails that should receive contact form submissions
     * @return array List of email addresses that have receive_contact_form enabled
     */
    public function getContactFormEmails(): array
    {
        $emails = [];
        $contactEmails = $this->contact_emails ?? [];
        
        // Handle new structure with individual preferences
        if (!empty($contactEmails) && isset($contactEmails[0]['email'])) {
            foreach ($contactEmails as $item) {
                if (($item['receive_contact_form'] ?? true) === true) {
                    $emails[] = $item['email'];
                }
            }
        }
        
        return $emails;
    }

    /**
     * Get emails that should receive transaction confirmations
     * @return array List of email addresses that have receive_transaction_emails enabled
     */
    public function getTransactionEmails(): array
    {
        $emails = [];
        $contactEmails = $this->contact_emails ?? [];
        
        // Handle new structure with individual preferences
        if (!empty($contactEmails) && isset($contactEmails[0]['email'])) {
            foreach ($contactEmails as $item) {
                if (($item['receive_transaction_emails'] ?? true) === true) {
                    $emails[] = $item['email'];
                }
            }
        }
        
        return $emails;
    }
    
    /**
     * Check if any email should receive contact form emails
     * @return bool True if at least one email has preference enabled
     */
    public function shouldReceiveContactFormEmails(): bool
    {
        return !empty($this->getContactFormEmails());
    }

    /**
     * Check if any email should receive transaction emails
     * @return bool True if at least one email has preference enabled
     */
    public function shouldReceiveTransactionEmails(): bool
    {
        return !empty($this->getTransactionEmails());
    }

    /**
     * Check if website is a fundraiser type
     */
    public function isFundraiser()
    {
        return $this->type === 'fundraiser';
    }

    /**
     * Check if website is an investment type
     */
    public function isInvestment()
    {
        return $this->type === 'investment';
    }

    /**
     * Check if website is a ticket type
     */
    public function isTicket()
    {
        return $this->type === 'ticket';
    }

    /**
     * Check if website is an auction type
     */
    public function isAuction()
    {
        return $this->type === 'auction';
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function setting()
    {
        return $this->belongsTo(Setting::class, 'user_id', 'user_id');
    }
    
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function header()
    {
        return $this->hasOne(Header::class, 'website_id', 'id');
    }

    public function footer()
    {
        return $this->hasOne(Footer::class, 'website_id', 'id');
    }

    /**
     * Get the newsletter subscriptions for this website
     */
    public function newsletterSubscriptions()
    {
        return $this->hasMany(NewsletterSubscription::class);
    }

    /**
     * Get only active newsletter subscriptions
     */
    public function activeNewsletterSubscriptions()
    {
        return $this->hasMany(NewsletterSubscription::class)->where('status', 'active');
    }

    /**
     * Get the payment settings for this website
     */
    public function paymentSettings()
    {
        return $this->hasOne(WebsitePaymentSetting::class);
    }

    /**
     * Get the email/SMTP settings for this website
     */
    public function emailSettings()
    {
        return $this->hasOne(\App\Models\WebsiteEmailSetting::class);
    }

    /**
     * Get the active payment configuration for this website
     * Falls back to settings table if no website-specific payment settings exist
     */
    public function getPaymentConfig()
    {
        $websitePaymentSettings = $this->paymentSettings;
        
        if ($websitePaymentSettings) {
            return $websitePaymentSettings->getPaymentConfig();
        }
        
        // Fallback to settings table (existing behavior)
        $setting = $this->setting;
        if ($setting) {
            if ($setting->payment_method === 'stripe') {
                return [
                    'publishable_key' => $setting->stripe_publishable_key,
                    'secret_key' => $setting->stripe_secret_key,
                ];
            } else {
                return [
                    'login_id' => $setting->authorize_login_id,
                    'transaction_key' => $setting->authorize_transaction_key,
                    'sandbox' => true, // Default to sandbox for fallback
                ];
            }
        }
        
        return [];
    }

    /**
     * Get the payment method for this website
     */
    public function getPaymentMethod()
    {
        $websitePaymentSettings = $this->paymentSettings;
        
        if ($websitePaymentSettings) {
            return $websitePaymentSettings->payment_method;
        }
        
        // Fallback to settings table
        $setting = $this->setting;
        return $setting ? $setting->payment_method : 'authorize';
    }

    /**
     * Get the processing fee for this website
     * Returns website-specific fee or falls back to global fee
     */
    public function getProcessingFee(): float
    {
        $websitePaymentSettings = $this->paymentSettings;
        
        if ($websitePaymentSettings) {
            return $websitePaymentSettings->getProcessingFee();
        }
        
        // Fallback to global payment settings
        $globalSettings = \App\Models\PaymentSetting::first();
        return $globalSettings ? (float) $globalSettings->fee : 2.9;
    }
}

