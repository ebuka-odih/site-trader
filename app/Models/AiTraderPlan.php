<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiTraderPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'number_of_traders',
        'stocks_trading',
        'investment_amount',
        'is_active',
        'features'
    ];

    protected $casts = [
        'stocks_trading' => 'array',
        'features' => 'array',
        'price' => 'decimal:2',
        'investment_amount' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function aiTraders()
    {
        return $this->hasMany(AiTrader::class);
    }

    public function getFormattedPriceAttribute()
    {
        return '$' . number_format($this->price, 2);
    }

    public function getStocksTradingListAttribute()
    {
        return is_array($this->stocks_trading) ? implode(', ', $this->stocks_trading) : '';
    }
}
