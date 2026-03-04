<?php

namespace App\Services;

use App\Models\PaymentFunnelEvent;
use App\Models\Website;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Agent;

class PaymentFunnelService
{
    protected $website;
    protected $agent;
    protected $geolocationService;
    
    public function __construct()
    {
        $this->agent = new Agent();
        $this->geolocationService = new GeolocationService();
        
        // Try multiple methods to find the website
        $host = request()->getHost();
        
        // First try exact domain match
        $this->website = Website::where('domain', $host)->first();
        
        // If not found, try without www prefix
        if (!$this->website && str_starts_with($host, 'www.')) {
            $this->website = Website::where('domain', substr($host, 4))->first();
        }
        
        // If still not found, try with www prefix
        if (!$this->website && !str_starts_with($host, 'www.')) {
            $this->website = Website::where('domain', 'www.' . $host)->first();
        }
        
        // If still not found, try to get from session or use first available website
        if (!$this->website) {
            if (session('website_id')) {
                $this->website = Website::find(session('website_id'));
            } else {
                $this->website = Website::first();
            }
        }
        
        // Log the website detection for debugging
        \Log::info('PaymentFunnelService: Website detected', [
            'host' => $host,
            'website_id' => $this->website ? $this->website->id : null,
            'website_domain' => $this->website ? $this->website->domain : null
        ]);
    }

    /**
     * Track a funnel event
     */
    public function trackEvent($step, $formType = null, $data = [])
    {
        if (!$this->website) {
            return false;
        }

        $sessionId = Session::getId();
        
        // Check if this exact event already exists for this session
        $existing = PaymentFunnelEvent::where('website_id', $this->website->id)
            ->where('session_id', $sessionId)
            ->where('funnel_step', $step)
            ->where('form_type', $formType)
            ->first();
            
        if ($existing && !in_array($step, [PaymentFunnelEvent::PAYMENT_COMPLETED, PaymentFunnelEvent::PAYMENT_FAILED])) {
            // Don't duplicate events except for payment completion/failure
            return $existing;
        }

        // Get visitor ID from cookie (Shopify approach)
        $visitorId = request()->cookie('_charity_visitor_id');
        
        // Get geolocation data from IP
        $ipAddress = request()->ip();
        $locationData = $this->geolocationService->getLocationFromIP($ipAddress);
        
        $eventData = [
            'website_id' => $this->website->id,
            'session_id' => $sessionId,
            'visitor_id' => $visitorId,
            'funnel_step' => $step,
            'form_type' => $formType,
            'completed_at' => now(),
            'referrer_url' => request()->headers->get('referer'),
            'utm_source' => request()->get('utm_source'),
            'utm_medium' => request()->get('utm_medium'),
            'utm_campaign' => request()->get('utm_campaign'),
            'device_type' => $this->getDeviceType(),
            'browser' => $this->agent->browser(),
            'ip_address' => $ipAddress,
            'country_code' => $locationData['country_code'],
            'country' => $locationData['country'],
            'state' => $locationData['state'],
            'city' => $locationData['city']
        ];

        // Add specific data based on step
        switch ($step) {
            case PaymentFunnelEvent::FORM_VIEW:
                // Just basic tracking
                break;
                
            case PaymentFunnelEvent::AMOUNT_ENTERED:
                $eventData['amount'] = $data['amount'] ?? null;
                $eventData['form_data'] = [
                    'amount' => $data['amount'] ?? null,
                    'fee_option' => $data['fee_option'] ?? null
                ];
                break;
                
            case PaymentFunnelEvent::PERSONAL_INFO_STARTED:
                $eventData['form_data'] = [
                    'first_name_filled' => !empty($data['first_name']),
                    'last_name_filled' => !empty($data['last_name']),
                    'email_filled' => !empty($data['email'])
                ];
                break;
                
            case PaymentFunnelEvent::PERSONAL_INFO_COMPLETED:
                $eventData['form_data'] = [
                    'first_name' => $data['first_name'] ?? null,
                    'last_name' => $data['last_name'] ?? null,
                    'email' => $data['email'] ?? null,
                    'phone' => $data['phone'] ?? null,
                    'address' => $data['address'] ?? null
                ];
                $eventData['user_id'] = $data['user_id'] ?? null;
                break;
                
            case PaymentFunnelEvent::PAYMENT_INITIATED:
                $eventData['amount'] = $data['amount'] ?? null;
                $eventData['payment_method'] = $data['payment_method'] ?? null;
                $eventData['form_data'] = $data['form_data'] ?? [];
                $eventData['user_id'] = $data['user_id'] ?? null;
                break;
                
            case PaymentFunnelEvent::PAYMENT_COMPLETED:
                $eventData['amount'] = $data['amount'] ?? null;
                $eventData['payment_method'] = $data['payment_method'] ?? null;
                $eventData['transaction_id'] = $data['transaction_id'] ?? null;
                $eventData['user_id'] = $data['user_id'] ?? null;
                break;
                
            case PaymentFunnelEvent::PAYMENT_FAILED:
                $eventData['amount'] = $data['amount'] ?? null;
                $eventData['payment_method'] = $data['payment_method'] ?? null;
                $eventData['error_message'] = $data['error_message'] ?? null;
                $eventData['user_id'] = $data['user_id'] ?? null;
                break;
        }

        return PaymentFunnelEvent::create($eventData);
    }

    /**
     * Track form view event
     */
    public function trackFormView($formType)
    {
        return $this->trackEvent(PaymentFunnelEvent::FORM_VIEW, $formType);
    }

    /**
     * Track amount entry
     */
    public function trackAmountEntered($formType, $amount, $feeOption = null)
    {
        return $this->trackEvent(PaymentFunnelEvent::AMOUNT_ENTERED, $formType, [
            'amount' => $amount,
            'fee_option' => $feeOption
        ]);
    }

    /**
     * Track personal info started (first field filled)
     */
    public function trackPersonalInfoStarted($formType, $formData)
    {
        return $this->trackEvent(PaymentFunnelEvent::PERSONAL_INFO_STARTED, $formType, $formData);
    }

    /**
     * Track personal info completed
     */
    public function trackPersonalInfoCompleted($formType, $formData)
    {
        return $this->trackEvent(PaymentFunnelEvent::PERSONAL_INFO_COMPLETED, $formType, $formData);
    }

    /**
     * Track payment initiated (form submitted)
     */
    public function trackPaymentInitiated($formType, $amount, $paymentMethod, $formData = [], $userId = null)
    {
        return $this->trackEvent(PaymentFunnelEvent::PAYMENT_INITIATED, $formType, [
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'form_data' => $formData,
            'user_id' => $userId
        ]);
    }

    /**
     * Track payment completed
     */
    public function trackPaymentCompleted($formType, $amount, $paymentMethod, $transactionId, $userId = null)
    {
        return $this->trackEvent(PaymentFunnelEvent::PAYMENT_COMPLETED, $formType, [
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'transaction_id' => $transactionId,
            'user_id' => $userId
        ]);
    }

    /**
     * Track payment failed
     */
    public function trackPaymentFailed($formType, $amount, $paymentMethod, $errorMessage, $userId = null)
    {
        return $this->trackEvent(PaymentFunnelEvent::PAYMENT_FAILED, $formType, [
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'error_message' => $errorMessage,
            'user_id' => $userId
        ]);
    }

    /**
     * Get device type
     */
    protected function getDeviceType()
    {
        if ($this->agent->isMobile()) {
            return 'mobile';
        } elseif ($this->agent->isTablet()) {
            return 'tablet';
        } else {
            return 'desktop';
        }
    }

    /**
     * Get current session's funnel progress
     */
    public function getSessionProgress($formType = null)
    {
        $sessionId = Session::getId();
        
        $query = PaymentFunnelEvent::where('website_id', $this->website->id)
            ->where('session_id', $sessionId);
            
        if ($formType) {
            $query->where('form_type', $formType);
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Check if user has completed a specific step
     */
    public function hasCompletedStep($step, $formType = null)
    {
        $sessionId = Session::getId();
        
        $query = PaymentFunnelEvent::where('website_id', $this->website->id)
            ->where('session_id', $sessionId)
            ->where('funnel_step', $step);
            
        if ($formType) {
            $query->where('form_type', $formType);
        }
        
        return $query->exists();
    }

    /**
     * Get last completed step for current session
     */
    public function getLastCompletedStep($formType = null)
    {
        $sessionId = Session::getId();
        
        $query = PaymentFunnelEvent::where('website_id', $this->website->id)
            ->where('session_id', $sessionId);
            
        if ($formType) {
            $query->where('form_type', $formType);
        }
        
        $lastEvent = $query->orderBy('created_at', 'desc')->first();
        
        return $lastEvent ? $lastEvent->funnel_step : null;
    }
}