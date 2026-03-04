<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{

    protected $fillable = [
        'user_id',
        'website_id',
        'template_id',
        'is_template',
        'template_name',
        'name',
        'state',
        'status',
        'position',
        'meta_title',
        'meta_description',
        'meta_image',
        'background_color',
        'default',
        'is_main_site',
        'is_homepage',
        'show_in_menu',
        'enable_confetti',
    ];

    protected $casts = [
        'state' => 'array',
        'is_template' => 'boolean',
        'is_main_site' => 'boolean',
        'is_homepage' => 'boolean',
        'show_in_menu' => 'boolean',
        'enable_confetti' => 'boolean',
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function template()
    {
        return $this->belongsTo(PageTemplate::class, 'template_id');
    }
    
    /**
     * Scope for main site pages
     */
    public function scopeMainSite($query)
    {
        return $query->where('is_main_site', true);
    }
    
    /**
     * Scope for website-specific pages
     */
    public function scopeWebsitePages($query)
    {
        return $query->where('is_main_site', false);
    }
    
    /**
     * Scope for pages belonging to a specific website
     */
    public function scopeForWebsite($query, $websiteId)
    {
        return $query->where('website_id', $websiteId)->where('is_main_site', false);
    }
    
    /**
     * Check if this is a main site page
     */
    public function isMainSitePage()
    {
        return $this->is_main_site;
    }
    
    /**
     * Save current page as template
     */
    public function saveAsTemplate($templateData)
    {
        return PageTemplate::create([
            'name' => $templateData['name'],
            'description' => $templateData['description'] ?? '',
            'state' => $this->state,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'background_color' => $this->background_color,
            'category' => $templateData['category'] ?? 'general',
            'created_by' => $this->user_id,
            'is_public' => $templateData['is_public'] ?? true,
        ]);
    }
    
    /**
     * Apply template to current page
     */
    public function applyTemplate(PageTemplate $template)
    {
        $this->update([
            'state' => $template->state,
            'meta_title' => $template->meta_title,
            'meta_description' => $template->meta_description,
            'background_color' => $template->background_color,
            'template_id' => $template->id,
        ]);
        
        $template->incrementUsage();
        
        return $this;
    }
    
    /**
     * Set this page as homepage for its website
     * Removes homepage status from other pages of the same website
     */
    public function setAsHomepage()
    {
        // Start a database transaction
        \DB::transaction(function () {
            // Remove homepage status from all other pages of this website
            if ($this->is_main_site) {
                // For main site pages, remove homepage from all main site pages
                static::mainSite()->where('id', '!=', $this->id)->update(['is_homepage' => false, 'default' => 0]);
            } else {
                // For website pages, remove homepage from all pages of the same website
                static::where('website_id', $this->website_id)
                    ->where('id', '!=', $this->id)
                    ->update(['is_homepage' => false, 'default' => 0]);
            }
            
            // Set this page as homepage
            $this->update([
                'is_homepage' => true,
                'default' => 1 // Keep backward compatibility
            ]);
        });
        
        return $this;
    }
    
    /**
     * Remove homepage status from this page
     */
    public function removeHomepageStatus()
    {
        $this->update([
            'is_homepage' => false,
            'default' => 0
        ]);
        
        return $this;
    }
    
    /**
     * Get the display title for the page
     * Returns "Home" if page is homepage, otherwise returns the page name
     */
    public function getDisplayTitle()
    {
        return $this->is_homepage ? 'Home' : $this->name;
    }
    
    /**
     * Scope for homepage pages
     */
    public function scopeHomepage($query)
    {
        return $query->where('is_homepage', true);
    }
}
