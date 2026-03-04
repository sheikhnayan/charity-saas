<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FraudRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rule_type',
        'parameters',
        'action',
        'risk_score',
        'is_active',
        'priority',
        'description',
    ];

    protected $casts = [
        'parameters' => 'array',
        'is_active' => 'boolean',
        'risk_score' => 'integer',
        'priority' => 'integer',
    ];

    /**
     * Get all detections for this rule
     */
    public function detections()
    {
        return $this->hasMany(FraudDetection::class);
    }

    /**
     * Scope for active rules
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for rules by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('rule_type', $type);
    }
}
