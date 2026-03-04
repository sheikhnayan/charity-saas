<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'ip_address',
        'end_time',
        'device_type',
        'browser',
        'ip_address',
        'initial_referrer',
        'landing_page',
        'utm_parameters',
    ];

    /**
     * Get the user that owns the session.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
