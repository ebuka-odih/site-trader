<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiTrader extends Model
{
    use HasFactory;

    protected $fillable = [
        'ai_trader_plan_id',
        'name',
        'trading_strategy',
        'ai_model',
        'ai_confidence',
        'ai_learning_mode',
        'stocks_to_trade',
        'risk_tolerance',
        'stop_loss_percentage',
        'take_profit_percentage',
        'max_positions',
        'position_size_percentage',
        'is_active',
        'current_performance',
        'total_trades',
        'winning_trades',
        'win_rate'
    ];

    protected $casts = [
        'stocks_to_trade' => 'array',
        'risk_tolerance' => 'decimal:2',
        'stop_loss_percentage' => 'decimal:2',
        'take_profit_percentage' => 'decimal:2',
        'position_size_percentage' => 'decimal:2',
        'current_performance' => 'decimal:4',
        'win_rate' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function aiTraderPlan()
    {
        return $this->belongsTo(AiTraderPlan::class);
    }

    public function getFormattedPerformanceAttribute()
    {
        return number_format($this->current_performance, 2) . '%';
    }

    public function getFormattedWinRateAttribute()
    {
        return number_format($this->win_rate, 2) . '%';
    }

    public function getStocksToTradeListAttribute()
    {
        return is_array($this->stocks_to_trade) ? implode(', ', $this->stocks_to_trade) : '';
    }
}
