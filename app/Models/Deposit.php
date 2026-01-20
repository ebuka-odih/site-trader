<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Deposit extends Model
{
    use HasFactory, HasUuids;

    public const STATUS_PENDING = 0;
    public const STATUS_APPROVED = 1;
    public const STATUS_DECLINED = 2;
    public const STATUS_IN_REVIEW = 3;

    protected $fillable = ['user_id', 'amount', 'payment_method_id', 'proof', 'status', 'wallet_type'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_DECLINED => 'Declined',
            self::STATUS_IN_REVIEW => 'In Review',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusLabels()[$this->status] ?? 'Pending';
    }

    public function getStatusBadgeTextAttribute(): string
    {
        return $this->status_label;
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            self::STATUS_APPROVED => '<span class="badge bg-success">Successful</span>',
            self::STATUS_DECLINED => '<span class="badge bg-danger">Declined</span>',
            self::STATUS_IN_REVIEW => '<span class="badge bg-info text-dark">In Review</span>',
            default => '<span class="badge bg-warning">Pending</span>',
        };
    }

    public function adminStatus()
    {
        return match ($this->status) {
            self::STATUS_APPROVED =>
                '<span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-green-400 border border-green-100 dark:border-green-500">Approved</span>',
            self::STATUS_DECLINED =>
                '<span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-red-100 dark:bg-gray-700 dark:border-red-500 dark:text-red-300">Declined</span>',
            self::STATUS_IN_REVIEW =>
                '<span class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-blue-100 dark:bg-gray-700 dark:border-blue-500 dark:text-blue-300">In Review</span>',
            default =>
                '<span class="bg-orange-100 text-orange-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-orange-100 dark:bg-gray-700 dark:border-orange-300 dark:text-orange-300">Pending</span>',
        };
    }
}
