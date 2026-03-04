<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'investor_name',
        'investor_email',
        'investor_phone',
        'investment_amount',
        'investor_type',
        'share_quantity',
        'deal_id',
        'status',
        'transaction_id',
        'payment_method',
        'investor_data',
        'kyc_status',
        'aml_status',
        'notes'
    ];

    protected $casts = [
        'investor_data' => 'array',
        'investment_amount' => 'decimal:2',
        'share_quantity' => 'integer'
    ];

    /**
     * Get the website that owns the investment.
     */
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * Investment status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * KYC/AML status constants
     */
    const KYC_PENDING = 'pending';
    const KYC_APPROVED = 'approved';
    const KYC_REJECTED = 'rejected';

    /**
     * Get formatted investment amount
     */
    public function getFormattedAmountAttribute()
    {
        return '$' . number_format($this->investment_amount, 2);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case self::STATUS_COMPLETED:
                return 'success';
            case self::STATUS_PROCESSING:
                return 'warning';
            case self::STATUS_FAILED:
            case self::STATUS_CANCELLED:
                return 'danger';
            default:
                return 'secondary';
        }
    }

    /**
     * Scope for completed investments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope for pending investments
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
}
