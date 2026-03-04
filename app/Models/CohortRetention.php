<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CohortRetention extends Model
{
    use HasFactory;

    protected $table = 'cohort_retention';

    protected $fillable = [
        'cohort_id',
        'period',
        'period_date',
        'retained_users',
        'retention_rate',
        'revenue',
        'transactions'
    ];

    protected $casts = [
        'period' => 'integer',
        'period_date' => 'date',
        'retained_users' => 'integer',
        'retention_rate' => 'decimal:2',
        'revenue' => 'decimal:2',
        'transactions' => 'integer'
    ];

    public function cohort()
    {
        return $this->belongsTo(Cohort::class);
    }
}
