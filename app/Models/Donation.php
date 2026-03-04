<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\TracksAnalytics;

class Donation extends Model
{
    use TracksAnalytics;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'amount',
        'tip_amount',
        'tip_percentage',
        'tip_enabled',
        'website_id',
        'user_id',
        'type',
        'status',
        'hide',
        'comment',
        'transaction_id',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'referrer_url'
    ];
    
    /**
     * Get total amount including tip
     */
    public function getTotalAmountAttribute()
    {
        return $this->amount + $this->tip_amount;
    }
    
    /**
     * Get base amount without tip
     */
    public function getBaseAmountAttribute()
    {
        return $this->amount;
    }

    protected static function booted()
    {
        static::created(function ($donation) {
            $donation->trackConversion(
                $donation->amount,
                'donation',
                [
                    'payment_method' => $donation->payment_method,
                    'status' => $donation->status,
                ]
            );
        });
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function website()
    {
        return $this->belongsTo(Website::class);
    }
}
