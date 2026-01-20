<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'amount_paid',
        'currency',
        'start_date',
        'end_date',
        'notes',
        'signal_quantity_remaining',
        'daily_signals_used',
        'last_signal_date',
        'cancelled_at',
        'renewed_at'
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'last_signal_date' => 'date',
        'cancelled_at' => 'datetime',
        'renewed_at' => 'datetime',
    ];

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the user that owns the plan subscription
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan details
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Scope for active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope for expired subscriptions
     */
    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    /**
     * Scope for cancelled subscriptions
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    /**
     * Check if the subscription is active
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if the subscription is expired
     */
    public function isExpired()
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    /**
     * Check if the subscription is cancelled
     */
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Get formatted amount paid
     */
    public function getFormattedAmountPaidAttribute()
    {
        return $this->currency . ' ' . number_format($this->amount_paid, 2);
    }

    /**
     * Get subscription duration in days
     */
    public function getDurationDaysAttribute()
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->diffInDays($this->end_date);
        }
        return null;
    }

    /**
     * Get remaining days
     */
    public function getRemainingDaysAttribute()
    {
        if ($this->end_date && $this->isActive()) {
            $remaining = now()->diffInDays($this->end_date, false);
            return $remaining > 0 ? $remaining : 0;
        }
        return null;
    }

    /**
     * Check if subscription is about to expire (within 7 days)
     */
    public function isExpiringSoon()
    {
        if ($this->end_date && $this->isActive()) {
            return now()->diffInDays($this->end_date, false) <= 7;
        }
        return false;
    }

    /**
     * Activate the subscription
     */
    public function activate()
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'start_date' => now(),
        ]);
    }

    /**
     * Cancel the subscription
     */
    public function cancel()
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
        ]);
    }

    /**
     * Expire the subscription
     */
    public function expire()
    {
        $this->update([
            'status' => self::STATUS_EXPIRED,
        ]);
    }
}
