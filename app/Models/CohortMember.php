<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CohortMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'cohort_id',
        'user_id',
        'joined_at',
        'lifetime_value',
        'transaction_count',
        'last_activity_at'
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'lifetime_value' => 'decimal:2',
        'transaction_count' => 'integer',
        'last_activity_at' => 'datetime'
    ];

    public function cohort()
    {
        return $this->belongsTo(Cohort::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
