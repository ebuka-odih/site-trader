<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BotTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'name',
        'description',
        'trading_type',
        'base_asset',
        'quote_asset',
        'strategy',
        'leverage',
        'trade_duration',
        'target_yield_percentage',
        'auto_close',
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
        'auto_restart',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'strategy_config' => 'array',
        'trading_days' => 'array',
        'metadata' => 'array',
        'auto_close' => 'boolean',
        'trading_24_7' => 'boolean',
        'auto_restart' => 'boolean',
        'is_active' => 'boolean',
        'leverage' => 'decimal:2',
        'target_yield_percentage' => 'decimal:2',
        'max_investment' => 'decimal:2',
        'daily_loss_limit' => 'decimal:2',
        'stop_loss_percentage' => 'decimal:2',
        'take_profit_percentage' => 'decimal:2',
        'min_trade_amount' => 'decimal:2',
        'max_trade_amount' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function toBotAttributes(int $userId): array
    {
        return [
            'user_id' => $userId,
            'name' => $this->name,
            'trading_type' => $this->trading_type,
            'base_asset' => $this->base_asset,
            'quote_asset' => $this->quote_asset,
            'strategy' => $this->strategy,
            'leverage' => $this->leverage,
            'trade_duration' => $this->trade_duration,
            'target_yield_percentage' => $this->target_yield_percentage,
            'auto_close' => $this->auto_close,
            'strategy_config' => $this->strategy_config,
            'max_investment' => $this->max_investment,
            'daily_loss_limit' => $this->daily_loss_limit,
            'stop_loss_percentage' => $this->stop_loss_percentage,
            'take_profit_percentage' => $this->take_profit_percentage,
            'min_trade_amount' => $this->min_trade_amount,
            'max_trade_amount' => $this->max_trade_amount,
            'max_open_trades' => $this->max_open_trades,
            'trading_24_7' => $this->trading_24_7,
            'auto_restart' => $this->auto_restart,
            'status' => 'active',
        ];
    }
}
