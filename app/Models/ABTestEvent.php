<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ABTestEvent extends Model
{
    use HasFactory;

    protected $table = 'ab_test_events';

    protected $fillable = [
        'test_id',
        'variant_id',
        'assignment_id',
        'event_type',
        'page_url',
        'event_data',
        'event_at'
    ];

    protected $casts = [
        'event_data' => 'array',
        'event_at' => 'datetime'
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
