<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'quantity',
        'price',
        'price_per_share',
        'price_per_share_label',
        'total_shares',
        'available_shares',
        'status',
        'user_id',
        'hide_until',
        'hide_after',
        'image',
        'documents',
        'description',
        'market',
        'website_id',
        'category_id',
        'features_heading',
        'page_bg_color'
    ];

    protected $casts = [
        'documents' => 'array',
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(TicketImage::class);
    }

    public function features()
    {
        return $this->hasMany(TicketFeature::class);
    }

    public function financials()
    {
        return $this->hasOne(PropertyFinancial::class);
    }

    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeForWebsite($query, $websiteId)
    {
        return $query->where('website_id', $websiteId);
    }
}
