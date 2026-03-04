<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TicketCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'website_id',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = $category->generateUniqueSlug($category->name, $category->website_id);
            }
        });
        
        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = $category->generateUniqueSlug($category->name, $category->website_id);
            }
        });
    }

    protected function generateUniqueSlug($name, $websiteId)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->where('website_id', $websiteId)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForWebsite($query, $websiteId)
    {
        return $query->where('website_id', $websiteId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
