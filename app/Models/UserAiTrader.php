<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAiTrader extends Model
{
    use HasFactory;

    protected $table = 'user_ai_traders';

    protected $fillable = [
        'user_id',
        'ai_trader_id',
        'ai_trader_plan_id',
        'investment_amount',
        'status',
        'activated_at',
        'paused_at',
        'stopped_at',
        'completed_at',
        'current_balance',
        'total_profit_loss',
        'total_trades_executed',
        'winning_trades',
        'win_rate',
        'settings'
    ];

    protected $casts = [
        'investment_amount' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'total_profit_loss' => 'decimal:2',
        'win_rate' => 'decimal:2',
        'activated_at' => 'datetime',
        'paused_at' => 'datetime',
        'stopped_at' => 'datetime',
        'completed_at' => 'datetime',
        'settings' => 'array'
    ];

    /**
     * Get the user that owns this AI Trader activation
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the AI Trader for this activation
     */
    public function aiTrader()
    {
        return $this->belongsTo(AiTrader::class);
    }

    /**
     * Get the AI Trader Plan for this activation
     */
    public function aiTraderPlan()
    {
        return $this->belongsTo(AiTraderPlan::class);
    }

    /**
     * Get the subscription for this activation
     */
    public function subscription()
    {
        return $this->belongsTo(AiTraderSubscription::class, 'ai_trader_plan_id', 'ai_trader_plan_id')
            ->where('user_id', $this->user_id);
    }

    /**
     * Check if activation is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if activation is paused
     */
    public function isPaused()
    {
        return $this->status === 'paused';
    }

    /**
     * Check if activation is stopped
     */
    public function isStopped()
    {
        return $this->status === 'stopped';
    }

    /**
     * Get formatted investment amount
     */
    public function getFormattedInvestmentAmountAttribute()
    {
        return '$' . number_format($this->investment_amount, 2);
    }

    /**
     * Get formatted current balance
     */
    public function getFormattedCurrentBalanceAttribute()
    {
        return '$' . number_format($this->current_balance ?? $this->investment_amount, 2);
    }

    /**
     * Get formatted profit/loss
     */
    public function getFormattedProfitLossAttribute()
    {
        $profitLoss = $this->total_profit_loss ?? 0;
        $sign = $profitLoss >= 0 ? '+' : '';
        return $sign . '$' . number_format($profitLoss, 2);
    }

    /**
     * Get formatted win rate
     */
    public function getFormattedWinRateAttribute()
    {
        return number_format($this->win_rate, 2) . '%';
    }
}