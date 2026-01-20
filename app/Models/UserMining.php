<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class UserMining extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'plan_id',
        'amount_invested',
        'currency',
        'hashrate',
        'equipment',
        'downtime',
        'electricity_costs',
        'start_date',
        'end_date',
        'status',
        'total_mined',
        'last_mining_date',
        'current_value',
        'notes',
    ];

    protected $casts = [
        'amount_invested' => 'decimal:8',
        'total_mined' => 'decimal:8',
        'current_value' => 'decimal:8',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'last_mining_date' => 'datetime',
    ];

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_SUSPENDED = 'suspended';

    /**
     * Get all status options
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_SUSPENDED => 'Suspended',
        ];
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'bg-green-100 text-green-800',
            self::STATUS_COMPLETED => 'bg-blue-100 text-blue-800',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800',
            self::STATUS_SUSPENDED => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', self::STATUS_SUSPENDED);
    }

    /**
     * Helper methods
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isSuspended()
    {
        return $this->status === self::STATUS_SUSPENDED;
    }

    public function isExpired()
    {
        return $this->end_date && $this->end_date->isPast();
    }

    public function isExpiringSoon()
    {
        return $this->end_date && $this->end_date->diffInDays(now()) <= 7;
    }

    public function getTimeUntilExpiryAttribute()
    {
        if (!$this->end_date) {
            return null;
        }
        return $this->end_date->diffForHumans();
    }

    /**
     * Formatting methods
     */
    public function getFormattedAmountInvestedAttribute()
    {
        return number_format($this->amount_invested, 8) . ' ' . $this->currency;
    }

    public function getFormattedTotalMinedAttribute()
    {
        return number_format($this->total_mined, 8) . ' ' . $this->currency;
    }

    public function getFormattedCurrentValueAttribute()
    {
        return number_format($this->current_value, 8) . ' ' . $this->currency;
    }

    public function getFormattedHashrateAttribute()
    {
        return $this->hashrate ?: 'N/A';
    }

    public function getFormattedEquipmentAttribute()
    {
        return $this->equipment ?: 'N/A';
    }

    public function getFormattedDowntimeAttribute()
    {
        return $this->downtime ?: 'N/A';
    }

    public function getFormattedElectricityCostsAttribute()
    {
        return $this->electricity_costs ?: 'N/A';
    }

    /**
     * Calculate mining progress
     */
    public function getMiningProgressAttribute()
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        $totalDuration = $this->start_date->diffInDays($this->end_date);
        $elapsedDuration = $this->start_date->diffInDays(now());

        if ($totalDuration <= 0) {
            return 100;
        }

        $progress = ($elapsedDuration / $totalDuration) * 100;
        return min(100, max(0, $progress));
    }

    /**
     * Calculate estimated daily mining rate
     */
    public function getEstimatedDailyMiningRateAttribute()
    {
        if (!$this->start_date || !$this->total_mined) {
            return 0;
        }

        $daysElapsed = max(1, $this->start_date->diffInDays(now()));
        return $this->total_mined / $daysElapsed;
    }

    /**
     * Calculate ROI percentage
     */
    public function getRoiPercentageAttribute()
    {
        if ($this->amount_invested <= 0) {
            return 0;
        }

        $totalValue = $this->current_value + $this->total_mined;
        $roi = (($totalValue - $this->amount_invested) / $this->amount_invested) * 100;
        return round($roi, 2);
    }

    /**
     * Check if mining can be cancelled
     */
    public function canBeCancelled()
    {
        return $this->isActive() && !$this->isExpired();
    }

    /**
     * Check if mining can be suspended
     */
    public function canBeSuspended()
    {
        return $this->isActive() && !$this->isExpired();
    }

    /**
     * Check if mining can be resumed
     */
    public function canBeResumed()
    {
        return $this->isSuspended() && !$this->isExpired();
    }
}
