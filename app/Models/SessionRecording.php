<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SessionRecording extends Model
{
    protected $fillable = [
        'website_id',
        'session_id',
        'visitor_id',
        'user_id',
        'url',
        'page_title',
        'duration_ms',
        'viewport_width',
        'viewport_height',
        'device_type',
        'browser',
        'os',
        'ip_address',
        'country',
        'country_code',
        'state',
        'city',
        'status',
        'has_rage_clicks',
        'has_errors',
        'event_count',
        'is_starred',
        'notes',
        'tags',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'has_rage_clicks' => 'boolean',
        'has_errors' => 'boolean',
        'is_starred' => 'boolean',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'duration_ms' => 'integer',
        'event_count' => 'integer',
    ];

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(SessionEvent::class)->orderBy('timestamp');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Hotjar-style helper methods
    public function getDurationInSeconds(): int
    {
        return (int) ($this->duration_ms / 1000);
    }

    public function getDurationFormatted(): string
    {
        $seconds = $this->getDurationInSeconds();
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;
        
        if ($minutes > 0) {
            return sprintf('%dm %ds', $minutes, $remainingSeconds);
        }
        
        return sprintf('%ds', $seconds);
    }

    public function isActive(): bool
    {
        return $this->status === 'recording';
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeWithRageClicks($query)
    {
        return $query->where('has_rage_clicks', true);
    }

    public function scopeWithErrors($query)
    {
        return $query->where('has_errors', true);
    }
}
