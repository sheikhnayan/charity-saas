<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniqueVisitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'session_id',
        'website_id',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'operating_system',
        'referrer',
        'landing_page',
        'country',
        'visited_at',
        'last_seen_at'
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'last_seen_at' => 'datetime'
    ];

    /**
     * Relationship with Website
     */
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * Relationship with PageViews
     */
    public function pageViews()
    {
        return $this->hasMany(PageView::class, 'visitor_id', 'visitor_id');
    }

    /**
     * Relationship with PaymentFunnelEvents
     */
    public function paymentFunnelEvents()
    {
        return $this->hasMany(PaymentFunnelEvent::class, 'visitor_id', 'visitor_id');
    }

    /**
     * Check if visitor is returning (has previous visits)
     */
    public function isReturning()
    {
        return $this->visited_at > $this->created_at;
    }

    /**
     * Get total sessions for this visitor
     */
    public function getTotalSessionsAttribute()
    {
        return $this->pageViews()->distinct('session_id')->count();
    }

    /**
     * Get total page views for this visitor
     */
    public function getTotalPageViewsAttribute()
    {
        return $this->pageViews()->count();
    }
}
