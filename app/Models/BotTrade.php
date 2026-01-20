<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotTrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'bot_trading_id',
        'user_id',
        'trade_id',
        'type',
        'base_asset',
        'quote_asset',
        'base_amount',
        'quote_amount',
        'price',
        'status',
        'execution_type',
        'related_trade_id',
        'profit_loss',
        'profit_loss_percentage',
        'executed_at',
        'cancelled_at',
        'metadata',
        'notes',
    ];

    protected $casts = [
        'base_amount' => 'decimal:8',
        'quote_amount' => 'decimal:2',
        'price' => 'decimal:8',
        'profit_loss' => 'decimal:2',
        'profit_loss_percentage' => 'decimal:4',
        'metadata' => 'array',
        'executed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Relationships
    public function botTrading(): BelongsTo
    {
        return $this->belongsTo(BotTrading::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function relatedTrade(): BelongsTo
    {
        return $this->belongsTo(BotTrade::class, 'related_trade_id');
    }

    // Helper Methods
    public function isBuy(): bool
    {
        return $this->type === 'buy';
    }

    public function isSell(): bool
    {
        return $this->type === 'sell';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isExecuted(): bool
    {
        return $this->status === 'executed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isProfitable(): bool
    {
        return $this->profit_loss > 0;
    }

    public function isLoss(): bool
    {
        return $this->profit_loss < 0;
    }

    public function isBreakEven(): bool
    {
        return $this->profit_loss == 0;
    }

    public function getTradingPairAttribute(): string
    {
        return $this->base_asset . '/' . $this->quote_asset;
    }

    public function getFormattedProfitLossAttribute(): string
    {
        $sign = $this->profit_loss >= 0 ? '+' : '';
        return $sign . '$' . number_format($this->profit_loss, 2);
    }

    public function getFormattedProfitLossPercentageAttribute(): string
    {
        $sign = $this->profit_loss_percentage >= 0 ? '+' : '';
        return $sign . number_format($this->profit_loss_percentage, 2) . '%';
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 8);
    }

    public function getFormattedBaseAmountAttribute(): string
    {
        return number_format($this->base_amount, 8) . ' ' . $this->base_asset;
    }

    public function getFormattedQuoteAmountAttribute(): string
    {
        return '$' . number_format($this->quote_amount, 2);
    }

    // Scopes
    public function scopeBuy($query)
    {
        return $query->where('type', 'buy');
    }

    public function scopeSell($query)
    {
        return $query->where('type', 'sell');
    }

    public function scopeExecuted($query)
    {
        return $query->where('status', 'executed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProfitable($query)
    {
        return $query->where('profit_loss', '>', 0);
    }

    public function scopeLoss($query)
    {
        return $query->where('profit_loss', '<', 0);
    }

    // Static Methods
    public static function generateTradeId(): string
    {
        return 'BOT_' . uniqid() . '_' . time();
    }
}
