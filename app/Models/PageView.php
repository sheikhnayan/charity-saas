<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'session_id',
        'website_id',
        'url',
        'page_title',
        'referrer',
        'viewed_at'
    ];

    protected $casts = [
        'viewed_at' => 'datetime'
    ];

    /**
     * Relationship with Website
     */
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * Relationship with UniqueVisitor
     */
    public function visitor()
    {
        return $this->belongsTo(UniqueVisitor::class, 'visitor_id', 'visitor_id');
    }

    /**
     * Scope for specific time period
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('viewed_at', [$startDate, $endDate]);
    }

    /**
     * Scope for specific website
     */
    public function scopeForWebsite($query, $websiteId)
    {
        return $query->where('website_id', $websiteId);
    }
}