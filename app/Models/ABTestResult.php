<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ABTestResult extends Model
{
    use HasFactory;

    protected $table = 'ab_test_results';

    protected $fillable = [
        'test_id',
        'variant_id',
        'impressions',
        'conversions',
        'conversion_rate',
        'total_revenue',
        'avg_revenue_per_user',
        'confidence_level',
        'p_value',
        'is_significant',
        'calculated_at'
    ];

    protected $casts = [
        'impressions' => 'integer',
        'conversions' => 'integer',
        'conversion_rate' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'avg_revenue_per_user' => 'decimal:2',
        'confidence_level' => 'decimal:2',
        'p_value' => 'decimal:8',
        'is_significant' => 'boolean',
        'calculated_at' => 'datetime'
    ];

    public function test()
    {
        return $this->belongsTo(ABTest::class, 'test_id');
    }

    public function variant()
    {
        return $this->belongsTo(ABTestVariant::class, 'variant_id');
    }
}
