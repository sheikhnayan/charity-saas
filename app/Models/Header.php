<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Header extends Model
{
    protected $fillable = [
        'user_id',
        'website_id', 
        'background', 
        'color', 
        'status', 
        'floating', 
        'menu',
        'logo_size',
        'show_investor_exclusives',
        'investor_exclusives_text',
        'investor_exclusives_url',
        'topbar_background_color',
        'topbar_text_color',
        'show_contact_topbar',
        'contact_phone',
        'contact_email',
        'contact_address',
        'contact_cta_text',
        'contact_cta_url',
        'contact_topbar_bg_color',
        'contact_topbar_text_color',
        'contact_cta_bg_color',
        'contact_cta_text_color',
        'invest_now_button_text',
        'header_font_family',
        'menu_font_family',
        'menu_font_size',
        'submenu_background_color',
        'contact_topbar_font_family',
        'investor_exclusives_font_family',
        'show_auth_button',
        'auth_button_text',
        'auth_button_bg_color',
        'auth_button_text_color',
        'builder_state',
        'use_builder'
    ];

    protected $casts = [
        'builder_state' => 'array',
        'use_builder' => 'boolean',
    ];

    public function setting()
    {
        return $this->belongsTo(Setting::class, 'user_id', 'user_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
