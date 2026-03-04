<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'url',
        'page_id',
        'target',
        'css_classes',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->where('status', 1)->orderBy('order');
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function getUrlAttribute($value)
    {
        // If page_id is set, generate URL from page
        if ($this->page_id && $this->page) {
            return '/page/' . str_replace(' ', '-', strtolower($this->page->name));
        }
        return $value;
    }
}
