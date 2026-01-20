<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserStaking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'plan_id',
        'amount_staked',
        'currency',
        'apy_rate',
        'start_date',
        'end_date',
        'status',
        'total_rewards',
        'last_reward_date',
        'current_value',
        'notes'
    ];

    protected $casts = [
        'amount_staked' => 'decimal:8',
        'apy_rate' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'last_reward_date' => 'datetime',
        'total_rewards' => 'decimal:8',
        'current_value' => 'decimal:8',
    ];

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    /**
     * Get status options
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_EXPIRED => 'Expired',
        ];
    }

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with Plan
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Check if staking is active
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if staking is completed
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if staking is cancelled
     */
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Check if staking is expired
     */
    public function isExpired()
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    /**
     * Check if staking is expiring soon (within 7 days)
     */
    public function isExpiringSoon()
    {
        if (!$this->end_date) {
            return false;
        }
        
        return $this->end_date->diffInDays(now()) <= 7;
    }

    /**
     * Get remaining days
     */
    public function getRemainingDaysAttribute()
    {
        if (!$this->end_date) {
            return null;
        }
        
        return max(0, $this->end_date->diffInDays(now()));
    }

    /**
     * Get time until expiry
     */
    public function getTimeUntilExpiryAttribute()
    {
        if (!$this->end_date) {
            return null;
        }
        
        return now()->diffForHumans($this->end_date, ['parts' => 2]);
    }

    /**
     * Calculate current value based on APY
     */
    public function calculateCurrentValue()
    {
        if (!$this->apy_rate || !$this->start_date) {
            return $this->amount_staked;
        }

        $daysStaked = $this->start_date->diffInDays(now());
        $dailyRate = $this->apy_rate / 365 / 100;
        $interestEarned = $this->amount_staked * $dailyRate * $daysStaked;
        
        return $this->amount_staked + $interestEarned;
    }

    /**
     * Get formatted amount staked
     */
    public function getFormattedAmountStakedAttribute()
    {
        return number_format($this->amount_staked, 8) . ' ' . $this->currency;
    }

    /**
     * Get formatted total rewards
     */
    public function getFormattedTotalRewardsAttribute()
    {
        return number_format($this->total_rewards, 8) . ' ' . $this->currency;
    }

    /**
     * Get formatted current value
     */
    public function getFormattedCurrentValueAttribute()
    {
        $currentValue = $this->current_value ?? $this->calculateCurrentValue();
        return number_format($currentValue, 8) . ' ' . $this->currency;
    }

    /**
     * Get formatted APY rate
     */
    public function getFormattedApyRateAttribute()
    {
        return $this->apy_rate ? number_format($this->apy_rate, 2) . '%' : 'N/A';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        switch ($this->status) {
            case self::STATUS_ACTIVE:
                return 'bg-green-100 text-green-800';
            case self::STATUS_COMPLETED:
                return 'bg-blue-100 text-blue-800';
            case self::STATUS_CANCELLED:
                return 'bg-red-100 text-red-800';
            case self::STATUS_EXPIRED:
                return 'bg-gray-100 text-gray-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    /**
     * Scope to get active stakings
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope to get completed stakings
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope to get stakings by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get stakings by plan
     */
    public function scopeByPlan($query, $planId)
    {
        return $query->where('plan_id', $planId);
    }
}
