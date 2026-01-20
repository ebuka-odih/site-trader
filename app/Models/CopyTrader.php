<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CopyTrader extends Model
{
    use HasFactory, HasUuids;
    
    protected $fillable = [
        'name', 
        'avatar', 
        'amount', 
        'win_rate', 
        'profit_share', 
        'win', 
        'loss',
        'status',
        'copiers_count'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'win_rate' => 'integer',
        'profit_share' => 'decimal:2',
        'win' => 'integer',
        'loss' => 'integer',
        'copiers_count' => 'integer',
    ];

    public function copied_trades()
    {
        return $this->hasMany(CopiedTrade::class, 'copy_trader_id');
    }

    public function active_copied_trades()
    {
        return $this->hasMany(CopiedTrade::class, 'copy_trader_id')->where('status', 1);
    }

    public function getTotalTradesAttribute()
    {
        return $this->win + $this->loss;
    }

    public function getSuccessRateAttribute()
    {
        $total = $this->total_trades;
        return $total > 0 ? round(($this->win / $total) * 100, 2) : 0;
    }

    public function isAvailable()
    {
        return $this->win_rate >= 50; // Only show traders with at least 50% win rate
    }

    public function getAvatarUrlAttribute()
    {
        if (!$this->avatar) {
            return asset('img/trader.jpg');
        }
        
        // If avatar starts with 'files/', it's an uploaded image in storage
        if (str_starts_with($this->avatar, 'files/')) {
            return asset('storage/' . $this->avatar);
        }
        
        // Otherwise, it's a seeded image in public directory
        return asset($this->avatar);
    }
}
