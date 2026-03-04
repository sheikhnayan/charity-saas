<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionEvent extends Model
{
    const UPDATED_AT = null; // Only created_at needed

    protected $fillable = [
        'session_recording_id',
        'timestamp',
        'event_type',
        'data',
        'action',
        'target_element',
        'x',
        'y',
        'scroll_x',
        'scroll_y',
    ];

    protected $casts = [
        'timestamp' => 'integer',
        'event_type' => 'integer',
        'x' => 'integer',
        'y' => 'integer',
        'scroll_x' => 'integer',
        'scroll_y' => 'integer',
    ];

    // rrweb event types (Hotjar standard)
    const EVENT_TYPE_DOM_CONTENT_LOADED = 0;
    const EVENT_TYPE_LOAD = 1;
    const EVENT_TYPE_FULL_SNAPSHOT = 2;
    const EVENT_TYPE_INCREMENTAL_SNAPSHOT = 3;
    const EVENT_TYPE_META = 4;
    const EVENT_TYPE_CUSTOM = 5;

    public function recording(): BelongsTo
    {
        return $this->belongsTo(SessionRecording::class, 'session_recording_id');
    }

    public function getDecodedData()
    {
        return json_decode($this->data, true);
    }

    public function isMouseMove(): bool
    {
        return $this->action === 'move';
    }

    public function isClick(): bool
    {
        return $this->action === 'click';
    }

    public function isScroll(): bool
    {
        return $this->action === 'scroll';
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('event_type', $type);
    }

    public function scopeWithAction($query, $action)
    {
        return $query->where('action', $action);
    }
}
