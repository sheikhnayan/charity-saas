<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteEmailSetting extends Model
{
    protected $fillable = [
        'website_id',
        'mailer',
        'host',
        'port',
        'encryption',
        'username',
        'password',
        'from_address',
        'from_name',
        'reply_to_address',
        'reply_to_name',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'username' => 'encrypted',
        'password' => 'encrypted',
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * Return a structured mail configuration array
     */
    public function getMailConfig(): array
    {
        return [
            'mailer' => $this->mailer ?: 'smtp',
            'host' => $this->host,
            'port' => (int)($this->port ?: 587),
            'encryption' => $this->encryption, // tls/ssl/null
            'username' => $this->username,
            'password' => $this->password,
            'from' => [
                'address' => $this->from_address,
                'name' => $this->from_name,
            ],
            'reply_to' => [
                'address' => $this->reply_to_address,
                'name' => $this->reply_to_name,
            ],
        ];
    }
}
