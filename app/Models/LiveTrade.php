<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveTrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'asset_type',
        'symbol',
        'order_type',
        'side',
        'quantity',
        'price',
        'amount',
        'leverage',
        'status',
        'filled_at',
        'entry_price',
        'exit_price',
        'profit_loss'
    ];

    protected $casts = [
        'quantity' => 'decimal:8',
        'price' => 'decimal:8',
        'amount' => 'decimal:8',
        'leverage' => 'decimal:2',
        'entry_price' => 'decimal:8',
        'exit_price' => 'decimal:8',
        'profit_loss' => 'decimal:2',
        'filled_at' => 'datetime'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper Methods
    public function isLimitOrder(): bool
    {
        return $this->order_type === 'limit';
    }

    public function isMarketOrder(): bool
    {
        return $this->order_type === 'market';
    }

    public function isBuyOrder(): bool
    {
        return $this->side === 'buy';
    }

    public function isSellOrder(): bool
    {
        return $this->side === 'sell';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isFilled(): bool
    {
        return $this->status === 'filled';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    public function isOpen(): bool
    {
        return in_array($this->status, ['pending', 'filled']);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'filled' => 'bg-green-100 text-green-800',
            'closed' => 'bg-blue-100 text-blue-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function adminStatus(): string
    {
        $statusColors = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'filled' => 'bg-green-100 text-green-800',
            'closed' => 'bg-blue-100 text-blue-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'rejected' => 'bg-red-100 text-red-800',
        ];

        $color = $statusColors[$this->status] ?? 'bg-gray-100 text-gray-800';
        return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' . $color . '">' . ucfirst($this->status) . '</span>';
    }

    public function getSideBadgeAttribute(): string
    {
        return $this->isBuyOrder() 
            ? 'bg-green-100 text-green-800' 
            : 'bg-red-100 text-red-800';
    }

    public function getOrderTypeBadgeAttribute(): string
    {
        return $this->isLimitOrder() 
            ? 'bg-blue-100 text-blue-800' 
            : 'bg-purple-100 text-purple-800';
    }
}
