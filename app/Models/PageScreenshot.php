<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageScreenshot extends Model
{
    protected $fillable = [
        'website_id',
        'page_url',
        'page_path',
        'screenshot_path',
        'viewport_width',
        'viewport_height',
        'device_type',
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function getScreenshotUrlAttribute()
    {
        return asset('storage/' . $this->screenshot_path);
    }
}
