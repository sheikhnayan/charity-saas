<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cohort extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'name',
        'type',
        'description',
        'definition',
        'start_date',
        'end_date',
        'is_active',
        'member_count'
    ];

    protected $casts = [
        'definition' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'member_count' => 'integer'
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function members()
    {
        return $this->hasMany(CohortMember::class);
    }

    public function retention()
    {
        return $this->hasMany(CohortRetention::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'cohort_members')
            ->withPivot('joined_at', 'lifetime_value', 'transaction_count', 'last_activity_at')
            ->withTimestamps();
    }
}
