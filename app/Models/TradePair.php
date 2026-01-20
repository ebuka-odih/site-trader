<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TradePair extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'type',
        'pair',
        'current_price',
        'price_change_24h'
    ];
    
    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function trade()
    {
        return $this->hasMany(Trade::class, 'trade_pair_id');
    }

}
