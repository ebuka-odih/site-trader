<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    // Crypto type constants
    const CRYPTO_TYPES = [
        'bitcoin' => 'Bitcoin (BTC)',
        'ethereum' => 'Ethereum (ETH)',
        'usdt' => 'Tether (USDT) BEP20',
        'usdt_erc20' => 'Tether (USDT) ERC20',
        'usdt_trc20' => 'Tether (USDT) TRC20',
        'usdc' => 'USD Coin (USDC)',
        'litecoin' => 'Litecoin (LTC)',
        'bitcoin_cash' => 'Bitcoin Cash (BCH)',
        'ethereum' => 'Ethereum (ETH)',
        'binance_coin' => 'Binance Coin (BNB)',
        'cardano' => 'Cardano (ADA)',
        'solana' => 'Solana (SOL)',
        'ripple' => 'Ripple (XRP)',
        'polkadot' => 'Polkadot (DOT)',
        'dogecoin' => 'Dogecoin (DOGE)',
        'avalanche' => 'Avalanche (AVAX)',
        'polygon' => 'Polygon (MATIC)'
    ];

    protected $fillable = ['wallet', 'crypto_type', 'address', 'bank', 'is_active'];

    protected $casts = [
        'bank' => 'array',
        'is_active' => 'boolean'
    ];

    public function deposits()
    {
        return $this->hasMany(Deposit::class, 'payment_method_id');
    }

    /**
     * Get the display name for the crypto type
     */
    public function getCryptoDisplayNameAttribute()
    {
        return self::CRYPTO_TYPES[$this->crypto_type] ?? $this->crypto_type;
    }

    /**
     * Get the crypto symbol
     */
    public function getCryptoSymbolAttribute()
    {
        $symbols = [
            'bitcoin' => 'BTC',
            'ethereum' => 'ETH',
            'binance_coin' => 'BNB',
            'cardano' => 'ADA',
            'solana' => 'SOL',
            'ripple' => 'XRP',
            'polkadot' => 'DOT',
            'dogecoin' => 'DOGE',
            'avalanche' => 'AVAX',
            'polygon' => 'MATIC'
        ];

        return $symbols[$this->crypto_type] ?? '';
    }
}
