<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsEvent extends Model
{
    protected $guarded = []; // Allow all fields to be filled
    
    // Alternative: use fillable with all fields
    // protected $fillable = [
    //     'event_type',
    //     'website_id', 
    //     'session_id',
    //     'page_url',
    //     'url',
    //     'user_agent',
    //     'ip_address',
    //     'user_id',
    //     'referrer',
    //     'referrer_url',
    //     'method',
    //     'utm_source',
    //     'utm_medium',
    //     'utm_campaign',
    //     'utm_term',
    //     'utm_content',
    //     'device_type',
    //     'browser',
    //     'os',
    //     'platform',
    //     'country',
    //     'city',
    //     'landing_page',
    //     'exit_page',
    //     'duration',      
    //     'is_bounce',
    //     'conversion_data',
    //     'event_data',
    //     'conversion_value',
    //     'meta_data'
    // ];

    protected $casts = [
        'conversion_data' => 'array',
        'event_data' => 'array',
        'meta_data' => 'array',
        'is_bounce' => 'boolean',
        'conversion_value' => 'decimal:2'
    ];
}
