<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol', 'name', 'type', 'current_price', 'market_cap',
        'volume_24h', 'price_change_24h', 'price_change_percentage_24h',
        'is_active'
    ];

    protected $casts = [
        'current_price' => 'decimal:8',
        'market_cap' => 'decimal:2',
        'volume_24h' => 'decimal:2',
        'price_change_24h' => 'decimal:4',
        'price_change_percentage_24h' => 'decimal:4',
        'is_active' => 'boolean',
        'last_updated' => 'datetime',
    ];

    public function holdings(): HasMany
    {
        return $this->hasMany(UserHolding::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(HoldingTransaction::class);
    }

    /**
     * Get users who favorited this asset
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(\App\Models\User::class, 'user_favorites')
            ->withTimestamps();
    }
}
