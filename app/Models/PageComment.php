<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_identifier',
        'component_id',
        'website_id',
        'author_name',
        'author_email',
        'comment',
        'is_approved',
        'is_anonymous',
        'is_admin_reply',
        'ip_address',
        'user_agent',
        'parent_id'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_anonymous' => 'boolean',
        'is_admin_reply' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship to Website
    public function website()
    {
        return $this->belongsTo(Website::class, 'website_id', 'id');
    }

    // Relationship for replies
    public function replies()
    {
        return $this->hasMany(PageComment::class, 'parent_id')
            ->where('is_approved', true)
            ->orderBy('created_at', 'asc');
    }

    // Relationship for parent comment
    public function parent()
    {
        return $this->belongsTo(PageComment::class, 'parent_id');
    }

    // Scope for approved comments
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    // Scope for top-level comments (not replies)
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    // Scope for filtering by website
    public function scopeForWebsite($query, $websiteId)
    {
        return $query->where('website_id', $websiteId);
    }

    // Get formatted author name
    public function getAuthorDisplayNameAttribute()
    {
        return $this->is_anonymous ? 'Anonymous' : $this->author_name;
    }

    // Get time ago format
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Scope for admin replies only
    public function scopeAdminReplies($query)
    {
        return $query->where('is_admin_reply', true);
    }

    // Scope for user comments only (not admin replies)
    public function scopeUserComments($query)
    {
        return $query->where('is_admin_reply', false);
    }

    // Get formatted author name with admin badge
    public function getAuthorWithBadgeAttribute()
    {
        $name = $this->is_anonymous ? 'Anonymous' : $this->author_name;
        return $this->is_admin_reply ? $name . ' (Admin)' : $name;
    }
}