<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BotTrading extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'base_asset',
        'quote_asset',
        'trading_type',
        'leverage',
        'trade_duration',
        'target_yield_percentage',
        'auto_close',
        'strategy',
        'status',
        'strategy_config',
        'max_investment',
        'daily_loss_limit',
        'stop_loss_percentage',
        'take_profit_percentage',
        'min_trade_amount',
        'max_trade_amount',
        'max_open_trades',
        'trading_24_7',
        'trading_start_time',
        'trading_end_time',
        'trading_days',
        'total_invested',
        'total_profit',
        'total_trades',
        'successful_trades',
        'success_rate',
        'participants_count',
        'auto_restart',
        'last_trade_at',
        'started_at',
        'stopped_at',
    ];

    protected $casts = [
        'strategy_config' => 'array',
        'trading_days' => 'array',
        'trading_24_7' => 'boolean',
        'auto_restart' => 'boolean',
        'auto_close' => 'boolean',
        'leverage' => 'decimal:2',
        'target_yield_percentage' => 'decimal:2',
        'max_investment' => 'decimal:2',
        'daily_loss_limit' => 'decimal:2',
        'stop_loss_percentage' => 'decimal:2',
        'take_profit_percentage' => 'decimal:2',
        'min_trade_amount' => 'decimal:2',
        'max_trade_amount' => 'decimal:2',
        'total_invested' => 'decimal:2',
        'total_profit' => 'decimal:2',
        'success_rate' => 'decimal:2',
        'participants_count' => 'integer',
        'last_trade_at' => 'datetime',
        'started_at' => 'datetime',
        'stopped_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trades(): HasMany
    {
        return $this->hasMany(BotTrade::class);
    }

    public function openTrades(): HasMany
    {
        return $this->hasMany(BotTrade::class)->where('status', 'pending');
    }

    public function completedTrades(): HasMany
    {
        return $this->hasMany(BotTrade::class)->where('status', 'executed');
    }

    // Helper Methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPaused(): bool
    {
        return $this->status === 'paused';
    }

    public function isStopped(): bool
    {
        return $this->status === 'stopped';
    }

    public function getTradingPairAttribute(): string
    {
        return $this->base_asset . '/' . $this->quote_asset;
    }

    public function getCurrentProfitAttribute(): float
    {
        return $this->total_profit;
    }

    public function getCurrentProfitPercentageAttribute(): float
    {
        if ($this->total_invested > 0) {
            return ($this->total_profit / $this->total_invested) * 100;
        }
        return 0;
    }

    public function getAvailableInvestmentAttribute(): float
    {
        return $this->max_investment - $this->total_invested;
    }

    public function canTrade(): bool
    {
        return $this->isActive() && $this->getAvailableInvestmentAttribute() > 0;
    }

    public function updatePerformance(): void
    {
        $completedTrades = $this->completedTrades;
        $this->total_trades = $completedTrades->count();
        $this->successful_trades = $completedTrades->where('profit_loss', '>', 0)->count();
        $this->total_profit = $completedTrades->sum('profit_loss');
        $this->success_rate = $this->total_trades > 0 ? ($this->successful_trades / $this->total_trades) * 100 : 0;
        $this->save();
    }

    // Strategy-specific methods
    public function getStrategyConfig(string $key = null)
    {
        if ($key) {
            return $this->strategy_config[$key] ?? null;
        }
        return $this->strategy_config;
    }

    public function setStrategyConfig(string $key, $value): void
    {
        $config = $this->strategy_config ?? [];
        $config[$key] = $value;
        $this->strategy_config = $config;
        $this->save();
    }
}
