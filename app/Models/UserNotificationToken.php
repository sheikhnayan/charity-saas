<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotificationToken extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'token_hash',
        'device_type',
        'browser',
        'device_name',
        'is_active',
        'last_used_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetime'
    ];

    /**
     * Get the user that owns the token
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hash the token for database storage
     */
    public static function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }

    /**
     * Scope to get only active tokens
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get tokens for a specific device type
     */
    public function scopeDeviceType($query, string $type)
    {
        return $query->where('device_type', $type);
    }
}
