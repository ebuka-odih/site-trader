<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'from_account',
        'address',
        'wallet',
        'bank',
        'paypal',
        'status',
    ];

    protected $casts = ['bank' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

     public function getStatusBadgeAttribute()
    {
        if ($this->status == 1)
        {
            return '<span class="badge bg-success text-white">Successful</span>';
        }elseif ($this->status == 2)
        {
            return '<span class="badge bg-danger text-white">Declined</span>';
        }
        return '<span class="badge bg-warning text-white">Pending</span>';
    }

    public function getStatusTextAttribute()
    {
        if ($this->status == 1)
        {
            return 'completed';
        }elseif ($this->status == 2)
        {
            return 'rejected';
        }
        return 'pending';
    }

    public function adminStatus()
    {
        if ($this->status == 1)
        {
            return '<span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-green-400 border border-green-100 dark:border-green-500">Approved</span>';
        }elseif ($this->status == 2)
        {
                 return '<span class="bg-orange-100 text-orange-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-orange-100 dark:bg-gray-700 dark:border-orange-300 dark:text-orange-300">Declined</span>';

        }
        return '<span class="bg-orange-100 text-orange-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-orange-100 dark:bg-gray-700 dark:border-orange-300 dark:text-orange-300">Pending</span>';
    }

}
