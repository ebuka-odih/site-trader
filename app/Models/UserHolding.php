<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserHolding extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'asset_id', 'quantity', 'average_buy_price',
        'total_invested', 'current_value', 'unrealized_pnl',
        'unrealized_pnl_percentage'
    ];

    protected $casts = [
        'quantity' => 'decimal:8',
        'average_buy_price' => 'decimal:8',
        'total_invested' => 'decimal:8',
        'current_value' => 'decimal:8',
        'unrealized_pnl' => 'decimal:8',
        'unrealized_pnl_percentage' => 'decimal:4',
        'last_updated' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(HoldingTransaction::class, 'asset_id', 'asset_id')
            ->where('user_id', $this->user_id);
    }
}
