<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HoldingTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'asset_id', 'type', 'quantity', 'price_per_unit',
        'total_amount', 'fee', 'status'
    ];

    protected $casts = [
        'quantity' => 'decimal:8',
        'price_per_unit' => 'decimal:8',
        'total_amount' => 'decimal:8',
        'fee' => 'decimal:8',
        'transaction_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
