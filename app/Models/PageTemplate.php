<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageTemplate extends Model
{
    protected $fillable = [
        'name',
        'description',
        'state',
        'meta_title',
        'meta_description',
        'background_color',
        'preview_image',
        'is_public',
        'usage_count',
        'category',
        'created_by',
    ];

    protected $casts = [
        'state' => 'array',
        'is_public' => 'boolean',
        'usage_count' => 'integer',
    ];

    /**
     * Increment usage count when template is applied
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    /**
     * Get templates by category
     */
    public static function getByCategory($category = null)
    {
        $query = self::where('is_public', true);
        
        if ($category) {
            $query->where('category', $category);
        }
        
        return $query->orderBy('usage_count', 'desc')->get();
    }
}
