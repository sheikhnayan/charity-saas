<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    protected $fillable = [
        'user_id',
        'website_id',
        'background',
        'background_type',
        'status',
        'color',
        'menu',
        'message',
        'copy_right',
        'social',
        'facebook',
        'instagram',
        'twitter',
        'linkedin',
        'youtube',
        'pinterest',
        'tiktok',
        'blue_sky',
        'disclaimer_text',
        'description_text',
        'background_image_desktop',
        'background_image_mobile',
        'investment_disclaimer',
        'privacy_page_id',
        'refund_page_id',
        'terms_page_id',
        'contact_heading',
        'contact_heading_color',
        'contact_heading_font',
        'contact_heading_size',
        'contact_email_color',
        'contact_email_font',
        'contact_email_size'
    ];

    /**
     * Get the privacy page for the footer.
     */
    public function privacy_page()
    {
        return $this->belongsTo(Page::class, 'privacy_page_id');
    }

    /**
     * Get the refund page for the footer.
     */
    public function refund_page()
    {
        return $this->belongsTo(Page::class, 'refund_page_id');
    }

    /**
     * Get the terms page for the footer.
     */
    public function terms_page()
    {
        return $this->belongsTo(Page::class, 'terms_page_id');
    }
}
