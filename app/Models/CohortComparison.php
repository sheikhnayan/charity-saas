<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CohortComparison extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'name',
        'cohort_ids',
        'metrics',
        'compared_at'
    ];

    protected $casts = [
        'cohort_ids' => 'array',
        'metrics' => 'array',
        'compared_at' => 'datetime'
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }
}
