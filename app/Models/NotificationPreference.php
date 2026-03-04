<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationPreference extends Model
{
    protected $fillable = [
        'user_id',
        'website_id',
        'donations_enabled',
        'auctions_enabled',
        'goals_enabled',
        'campaigns_enabled',
        'investments_enabled',
        'tickets_enabled',
        'general_enabled',
        'frequency',
        'quiet_hours_start',
        'quiet_hours_end'
    ];

    protected $casts = [
        'donations_enabled' => 'boolean',
        'auctions_enabled' => 'boolean',
        'goals_enabled' => 'boolean',
        'campaigns_enabled' => 'boolean',
        'investments_enabled' => 'boolean',
        'tickets_enabled' => 'boolean',
        'general_enabled' => 'boolean',
        'quiet_hours_start' => 'datetime:H:i',
        'quiet_hours_end' => 'datetime:H:i'
    ];

    /**
     * Get the user that owns the preferences
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if a notification type is enabled
     */
    public function isEnabled(string $type): bool
    {
        $field = $type . '_enabled';
        return $this->{$field} ?? false;
    }

    /**
     * Check if currently in quiet hours
     */
    public function isInQuietHours(): bool
    {
        if (!$this->quiet_hours_start || !$this->quiet_hours_end) {
            return false;
        }

        $now = now()->format('H:i');
        $start = $this->quiet_hours_start->format('H:i');
        $end = $this->quiet_hours_end->format('H:i');

        if ($start < $end) {
            return $now >= $start && $now <= $end;
        } else {
            // Overnight quiet hours
            return $now >= $start || $now <= $end;
        }
    }

    /**
     * Get default preferences
     */
    public static function defaults(): array
    {
        return [
            'donations_enabled' => true,
            'auctions_enabled' => true,
            'goals_enabled' => true,
            'campaigns_enabled' => true,
            'investments_enabled' => true,
            'tickets_enabled' => true,
            'general_enabled' => true,
            'frequency' => 'realtime',
            'quiet_hours_start' => null,
            'quiet_hours_end' => null
        ];
    }
}
