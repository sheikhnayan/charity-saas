<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeatmapData extends Model
{
    const UPDATED_AT = null; // Only created_at needed

    protected $fillable = [
        'website_id',
        'page_url',
        'page_path',
        'event_type',
        'x',
        'y',
        'viewport_width',
        'viewport_height',
        'element_selector',
        'element_text',
        'element_class',
        'element_id',
        'scroll_depth',
        'max_scroll',
        'duration_ms',
        'device_type',
        'session_id',
        'visitor_id',
    ];

    protected $casts = [
        'x' => 'integer',
        'y' => 'integer',
        'viewport_width' => 'integer',
        'viewport_height' => 'integer',
        'scroll_depth' => 'integer',
        'max_scroll' => 'integer',
        'duration_ms' => 'integer',
    ];

    // Hotjar event types
    const TYPE_CLICK = 'click';
    const TYPE_MOVE = 'move';
    const TYPE_SCROLL = 'scroll';
    const TYPE_ATTENTION = 'attention';

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function scopeClicks($query)
    {
        return $query->where('event_type', self::TYPE_CLICK);
    }

    public function scopeMoves($query)
    {
        return $query->where('event_type', self::TYPE_MOVE);
    }

    public function scopeScrolls($query)
    {
        return $query->where('event_type', self::TYPE_SCROLL);
    }

    public function scopeForPage($query, $pagePath)
    {
        return $query->where('page_path', $pagePath);
    }

    public function scopeForWebsite($query, $websiteId)
    {
        return $query->where('website_id', $websiteId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Normalize coordinates for different viewport sizes
    public function getNormalizedCoordinates($targetWidth, $targetHeight)
    {
        if (!$this->x || !$this->y) {
            return null;
        }

        $normalizedX = ($this->x / $this->viewport_width) * $targetWidth;
        $normalizedY = ($this->y / $this->viewport_height) * $targetHeight;

        return [
            'x' => round($normalizedX),
            'y' => round($normalizedY),
        ];
    }
}
