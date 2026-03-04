<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    protected $fillable = [
        'website_id',
        'title',
        'description',
        'dead_line',
        'value',
        'timezone',
        'status',
        'page_bg_color'
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function images()
    {
        return $this->hasMany(AuctionImage::class, 'auction_id');
    }
}
