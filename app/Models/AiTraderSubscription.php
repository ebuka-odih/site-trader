<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiTraderSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ai_trader_plan_id',
        'status',
        'monthly_fee',
        'subscribed_at',
        'expires_at',
        'cancelled_at',
        'payment_details'
    ];

    protected $casts = [
        'monthly_fee' => 'decimal:2',
        'subscribed_at' => 'datetime',
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'payment_details' => 'array'
    ];

    /**
     * Get the user that owns the subscription
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the AI Trader Plan for this subscription
     */
    public function aiTraderPlan()
    {
        return $this->belongsTo(AiTraderPlan::class);
    }

    /**
     * Get all AI Trader activations for this subscription
     */
    public function aiTraderActivations()
    {
        return $this->hasMany(UserAiTrader::class, 'ai_trader_plan_id', 'ai_trader_plan_id')
            ->where('user_id', $this->user_id);
    }

    /**
     * Check if subscription is active
     */
    public function isActive()
    {
        return $this->status === 'active' && $this->expires_at && $this->expires_at > now();
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at <= now();
    }

    /**
     * Get formatted monthly fee
     */
    public function getFormattedMonthlyFeeAttribute()
    {
        return '$' . number_format($this->monthly_fee, 2);
    }

    /**
     * Get days remaining until expiration
     */
    public function getDaysRemainingAttribute()
    {
        if (!$this->expires_at || $this->isExpired()) {
            return 0;
        }
        
        return now()->diffInDays($this->expires_at);
    }
}