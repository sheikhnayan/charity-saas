<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ABTestVariant extends Model
{
    use HasFactory;

    protected $table = 'ab_test_variants';

    protected $fillable = [
        'test_id',
        'name',
        'configuration',
        'is_control',
        'traffic_percentage'
    ];

    protected $casts = [
        'configuration' => 'array',
        'is_control' => 'boolean',
        'traffic_percentage' => 'integer'
    ];

    public function test()
    {
        return $this->belongsTo(ABTest::class, 'test_id');
    }

    public function assignments()
    {
        return $this->hasMany(ABTestAssignment::class, 'variant_id');
    }

    public function conversions()
    {
        return $this->hasMany(ABTestConversion::class, 'variant_id');
    }

    public function results()
    {
        return $this->hasMany(ABTestResult::class, 'variant_id');
    }
}
