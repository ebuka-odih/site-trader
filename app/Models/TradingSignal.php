<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TradingSignal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'plan_id',
        'title',
        'symbol',
        'type', // buy, sell
        'status', // active, completed, cancelled, expired
        'entry_price',
        'take_profit',
        'stop_loss',
        'expires_at',
        'created_by', // admin user who created the signal
        'is_active'
    ];

    protected $casts = [
        'entry_price' => 'decimal:8',
        'take_profit' => 'decimal:8',
        'stop_loss' => 'decimal:8',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Signal types
    const TYPE_BUY = 'buy';
    const TYPE_SELL = 'sell';

    // Signal statuses
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    // Market conditions
    const MARKET_BULLISH = 'bullish';
    const MARKET_BEARISH = 'bearish';
    const MARKET_SIDEWAYS = 'sideways';

    /**
     * Get signal types
     */
    public static function getTypes()
    {
        return [
            self::TYPE_BUY => 'Buy',
            self::TYPE_SELL => 'Sell',
        ];
    }

    /**
     * Get signal statuses
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
     * Get market conditions
     */
    public static function getMarketConditions()
    {
        return [
            self::MARKET_BULLISH => 'Bullish',
            self::MARKET_BEARISH => 'Bearish',
            self::MARKET_SIDEWAYS => 'Sideways',
        ];
    }

    /**
     * Get timeframes
     */
    public static function getTimeframes()
    {
        return [
            '1m' => '1 Minute',
            '5m' => '5 Minutes',
            '15m' => '15 Minutes',
            '30m' => '30 Minutes',
            '1h' => '1 Hour',
            '4h' => '4 Hours',
            '1d' => '1 Day',
            '1w' => '1 Week',
        ];
    }

    /**
     * Scope to get active signals
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                    ->where('is_active', true)
                    ->where('expires_at', '>', now());
    }

    /**
     * Scope to get signals by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get premium signals
     */
    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }

    /**
     * Scope to get signals by plan
     */
    public function scopeByPlan($query, $planId)
    {
        return $query->where('plan_id', $planId);
    }

    /**
     * Check if signal is expired
     */
    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if signal is still valid (not expired)
     */
    public function getIsValidAttribute()
    {
        return !$this->is_expired && $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Scope to get valid (non-expired) signals
     */
    public function scopeValid($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                    ->where('expires_at', '>', now());
    }

    /**
     * Scope to get expired signals
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Get time until expiry
     */
    public function getTimeUntilExpiryAttribute()
    {
        if (!$this->expires_at) {
            return null;
        }
        
        return now()->diffForHumans($this->expires_at, ['parts' => 2]);
    }

    /**
     * Check if signal is about to expire (within 1 hour)
     */
    public function getIsExpiringSoonAttribute()
    {
        if (!$this->expires_at) {
            return false;
        }
        
        return $this->expires_at->diffInHours(now()) <= 1;
    }

    /**
     * Get formatted entry price
     */
    public function getFormattedEntryPriceAttribute()
    {
        return number_format($this->entry_price, 2);
    }

    /**
     * Get formatted exit price
     */
    public function getFormattedExitPriceAttribute()
    {
        return $this->exit_price ? number_format($this->exit_price, 2) : 'N/A';
    }

    /**
     * Get formatted stop loss
     */
    public function getFormattedStopLossAttribute()
    {
        return $this->stop_loss ? number_format($this->stop_loss, 2) : 'N/A';
    }

    /**
     * Get formatted take profit
     */
    public function getFormattedTakeProfitAttribute()
    {
        return $this->take_profit ? number_format($this->take_profit, 2) : 'N/A';
    }

    /**
     * Get signal strength stars
     */
    public function getSignalStrengthStarsAttribute()
    {
        return str_repeat('★', $this->signal_strength) . str_repeat('☆', 5 - $this->signal_strength);
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
     * Get type badge class
     */
    public function getTypeBadgeClassAttribute()
    {
        return $this->type === self::TYPE_BUY ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
    }

    /**
     * Calculate potential profit/loss
     */
    public function calculatePotentialProfitLoss($currentPrice = null)
    {
        if (!$this->entry_price || !$this->take_profit) {
            return null;
        }

        $price = $currentPrice ?? $this->take_profit;
        
        if ($this->type === self::TYPE_BUY) {
            return (($price - $this->entry_price) / $this->entry_price) * 100;
        } else {
            return (($this->entry_price - $price) / $this->entry_price) * 100;
        }
    }

    /**
     * Relationship with Plan
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Relationship with User (creator)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship with UserPlans (subscribers)
     */
    public function subscribers()
    {
        return $this->hasMany(UserPlan::class, 'plan_id', 'plan_id');
    }
}
