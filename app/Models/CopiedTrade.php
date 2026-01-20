<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CopiedTrade extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'copy_trader_id',
        'amount',
        'trade_count',
        'win',
        'loss',
        'pnl',
        'status',
        'stopped_at'
    ];

    protected $casts = [
        'stopped_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'trade_count' => 'integer',
        'win' => 'integer',
        'loss' => 'integer',
        'pnl' => 'decimal:2',
        'status' => 'integer',
    ];

    public function copy_trader()
    {
        return $this->belongsTo(CopyTrader::class, 'copy_trader_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isActive()
    {
        return $this->status == 1;
    }

    public function isStopped()
    {
        return $this->status == 0;
    }

    public function pnl_histories()
    {
        return $this->hasMany(CopiedTradePnlHistory::class, 'copied_trade_id');
    }
}
