<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Trade extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'trade_pair_id', 'amount', 'status', 'leverage', 'duration', 'action_type', 'stop_loss', 'take_profit'];

    protected $keyType = 'string';
    public $incrementing = false;
    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trade_pair()
    {
        return $this->belongsTo(TradePair::class);
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->status == 'open')
        {
            return '<span class="badge badge-warning">Open</span>';
        }
        return '<span class="badge badge-success">Closed</span>';
    }
    public function adminStatus()
    {
        if ($this->status == 'closed')
        {
            return '<span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-green-400 border border-green-100 dark:border-green-500">Closed</span>';
        }
        return '<span class="bg-orange-100 text-orange-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-orange-100 dark:bg-gray-700 dark:border-orange-300 dark:text-orange-300">Open</span>';

    }

}
