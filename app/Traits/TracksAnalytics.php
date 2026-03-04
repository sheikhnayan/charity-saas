<?php

namespace App\Traits;

trait TracksAnalytics
{
    protected function trackConversion($amount, $type, $additionalData = [])
    {
        try {
            $websiteId = $this->website_id ?? request()->website_id ?? null;
            
            if (!$websiteId) {
                return;
            }

            $event = new \App\Models\AnalyticsEvent();
            $event->event_type = 'conversion';
            $event->website_id = $websiteId;
            $event->session_id = session()->getId();
            $event->user_id = auth()->id();
            $event->url = request()->fullUrl();
            
            // Conversion specific data
            $event->conversion_data = array_merge([
                'amount' => $amount,
                'type' => $type,
                'model' => class_basename($this),
                'model_id' => $this->id,
            ], $additionalData);
            
            // Client info
            $event->ip_address = request()->ip();
            $event->referrer_url = request()->header('referer');
            $event->user_agent = request()->header('User-Agent');
            
            $event->save();
        } catch (\Exception $e) {
            \Log::error('Failed to track conversion: ' . $e->getMessage());
        }
    }
}