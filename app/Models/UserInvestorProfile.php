<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInvestorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'investor_type',
        'investor_data',
    ];

    protected $casts = [
        'investor_data' => 'array',
    ];

    /**
     * Get the user that owns the investor profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
