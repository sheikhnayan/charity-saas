<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CryptoPayment extends Model
{
    protected $fillable = [
        'charge_code',
        'charge_id',
        'payment_type',
        'reference_id',
        'user_id',
        'website_id',
        'amount',
        'currency',
        'status',
        'hosted_url',
        'session_id',
        'charge_data',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'charge_data' => 'array',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the payment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the website associated with the payment
     */
    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * Get the reference model (Donation, Ticket, Auction, or Investment)
     */
    public function reference()
    {
        $modelMap = [
            'donation' => Donation::class,
            'ticket' => Ticket::class,
            'auction' => Auction::class,
            'investment' => Investment::class,
        ];

        $modelClass = $modelMap[$this->payment_type] ?? null;

        if ($modelClass && class_exists($modelClass)) {
            return $modelClass::find($this->reference_id);
        }

        return null;
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for completed payments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for failed payments
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for a specific payment type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('payment_type', $type);
    }

    /**
     * Check if payment is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment has failed
     */
    public function hasFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Mark payment as completed
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark payment as failed
     */
    public function markAsFailed(): void
    {
        $this->update([
            'status' => 'failed',
        ]);
    }

    /**
     * Get cryptocurrency from charge data
     */
    public function getCryptocurrency(): ?string
    {
        if (!$this->charge_data || !isset($this->charge_data['payments'])) {
            return null;
        }

        $payments = $this->charge_data['payments'];
        return $payments[0]['network'] ?? null;
    }

    /**
     * Get the amount paid in cryptocurrency
     */
    public function getCryptoAmount(): ?string
    {
        if (!$this->charge_data || !isset($this->charge_data['payments'])) {
            return null;
        }

        $payments = $this->charge_data['payments'];
        return $payments[0]['value']['crypto']['amount'] ?? null;
    }

    /**
     * Get transaction hash
     */
    public function getTransactionHash(): ?string
    {
        if (!$this->charge_data || !isset($this->charge_data['payments'])) {
            return null;
        }

        $payments = $this->charge_data['payments'];
        return $payments[0]['transaction_id'] ?? null;
    }
}
