<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    protected $guarded;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function maxTrade()
    {
        if ($this->trade_limit_per_day > 100)
        {
            return "UNLIMITED";
        }
        return $this->trade_limit_per_day;
    }
}
