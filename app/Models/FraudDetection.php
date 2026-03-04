<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FraudDetection extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'donation_id',
        'user_id',
        'fraud_rule_id',
        'status',
        'risk_score',
        'detection_details',
        'notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'detection_details' => 'array',
        'risk_score' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the fraud rule
     */
    public function fraudRule()
    {
        return $this->belongsTo(FraudRule::class);
    }

    /**
     * Get the transaction
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Get the donation
     */
    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reviewer
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scope for flagged detections
     */
    public function scopeFlagged($query)
    {
        return $query->where('status', 'flagged');
    }

    /**
     * Scope for high risk
     */
    public function scopeHighRisk($query)
    {
        return $query->where('risk_score', '>=', 70);
    }

    /**
     * Scope for pending review
     */
    public function scopePendingReview($query)
    {
        return $query->whereIn('status', ['flagged', 'reviewing']);
    }
}
