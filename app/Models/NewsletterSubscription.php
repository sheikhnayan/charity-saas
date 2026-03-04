<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewsletterSubscription extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'country_code',
        'website_id',
        'status',
        'subscribed_at'
    ];

    protected $casts = [
        'subscribed_at' => 'datetime',
    ];

    /**
     * Get the website that this subscription belongs to
     */
    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * Scope to get only active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get subscriptions for a specific website
     */
    public function scopeForWebsite($query, $websiteId)
    {
        return $query->where('website_id', $websiteId);
    }
}
