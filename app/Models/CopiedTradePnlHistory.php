<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CopiedTradePnlHistory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'copied_trade_id',
        'pnl',
        'description',
    ];

    protected $casts = [
        'pnl' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function copied_trade()
    {
        return $this->belongsTo(CopiedTrade::class, 'copied_trade_id');
    }
}
