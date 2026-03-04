<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'user_id', 'address', 'website_name', 'website_description', 'logo',
        'donate_button_text', 'phone_number', 'email', 'goal', 'description',
        'banner', 'title', 'title2', 'sub_title', 'date', 'location', 'time',
        'theme', 'font', 'payout_option', 'site_status', 'payment_method',
        'stripe_publishable_key', 'stripe_secret_key', 'authorize_login_id',
        'authorize_transaction_key', 'refund', 'privacy', 'terms', 'investment_disclaimer',
        'investment_title', 'asset_type', 'offering_type', 'asset_type_label', 'offering_type_label', 'additional_information',
        'invest_page_title', 'invest_amount_title', 'share_price_label', 'minimum_investment_label'
    ];

    public function website()
    {
        return $this->belongsTo(Website::class, 'user_id', 'user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function header()
    {
        return $this->hasOne(Header::class, 'user_id', 'user_id');
    }

    public function refund_page(){
        return $this->belongsTo(Page::class,'refund','id');
    }

    public function privacy_page(){
        return $this->belongsTo(Page::class,'privacy','id');
    }

    public function terms_page(){
        return $this->belongsTo(Page::class,'terms','id');
    }
}
