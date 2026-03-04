<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ABTest extends Model
{
    use HasFactory;

    protected $table = 'ab_tests';

    protected $fillable = [
        'website_id',
        'name',
        'description',
        'test_type',
        'variants',
        'traffic_split',
        'status',
        'goal_metric',
        'goal_value',
        'min_sample_size',
        'confidence_level',
        'started_at',
        'ended_at',
        'winning_variant_id'
    ];

    protected $casts = [
        'variants' => 'array',
        'traffic_split' => 'array',
        'goal_value' => 'decimal:2',
        'min_sample_size' => 'integer',
        'confidence_level' => 'decimal:2',
        'started_at' => 'datetime',
        'ended_at' => 'datetime'
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function testVariants()
    {
        return $this->hasMany(ABTestVariant::class, 'test_id');
    }

    public function assignments()
    {
        return $this->hasMany(ABTestAssignment::class, 'test_id');
    }

    public function conversions()
    {
        return $this->hasMany(ABTestConversion::class, 'test_id');
    }

    public function results()
    {
        return $this->hasMany(ABTestResult::class, 'test_id');
    }

    public function events()
    {
        return $this->hasMany(ABTestEvent::class, 'test_id');
    }

    public function winningVariant()
    {
        return $this->belongsTo(ABTestVariant::class, 'winning_variant_id');
    }
}
