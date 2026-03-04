<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomFont extends Model
{
    protected $fillable = [
        'font_name',
        'font_family',
        'file_path',
        'file_format',
        'file_size',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the full URL to the font file
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Scope to get only active fonts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
