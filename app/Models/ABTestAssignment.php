<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ABTestAssignment extends Model
{
    use HasFactory;

    protected $table = 'ab_test_assignments';

    protected $fillable = [
        'test_id',
        'variant_id',
        'user_identifier',
        'identifier_type',
        'user_id',
        'session_id',
        'assigned_at'
    ];

    protected $casts = [
        'assigned_at' => 'datetime'
    ];

    public function test()
    {
        return $this->belongsTo(ABTest::class, 'test_id');
    }

    public function variant()
    {
        return $this->belongsTo(ABTestVariant::class, 'variant_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conversions()
    {
        return $this->hasMany(ABTestConversion::class, 'assignment_id');
    }
}
