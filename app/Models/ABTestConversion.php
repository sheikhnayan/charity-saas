<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ABTestConversion extends Model
{
    use HasFactory;

    protected $table = 'ab_test_conversions';

    protected $fillable = [
        'test_id',
        'variant_id',
        'assignment_id',
        'conversion_type',
        'conversion_value',
        'metadata',
        'converted_at'
    ];

    protected $casts = [
        'conversion_value' => 'decimal:2',
        'metadata' => 'array',
        'converted_at' => 'datetime'
    ];

    public function test()
    {
        return $this->belongsTo(ABTest::class, 'test_id');
    }

    public function variant()
    {
        return $this->belongsTo(ABTestVariant::class, 'variant_id');
    }

    public function assignment()
    {
        return $this->belongsTo(ABTestAssignment::class, 'assignment_id');
    }
}
