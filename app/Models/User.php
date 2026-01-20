<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Models\Referral;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids;

    public function IsAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'status',
        'balance', // Holding account
        'trading_balance',
        'mining_balance',
        'referral_balance',
        'holding_balance',
        'staking_balance',
        'profit',
        'phone',
        'profile_image',
        'country',
        'telegram',
        'avatar',
        'subscription',
        'referral_code',
        'referred_by_id',
        'package_id',
        'currency',
        'trader',
        'trade_count',
        'email_verified_at',
        'verification_code',
        'verification_code_expires_at',
        // KYC fields
        'date_of_birth',
        'nationality',
        'street_address',
        'city',
        'state',
        'postal_code',
        'id_type',
        'id_number',
        'id_front',
        'id_back',
        'selfie',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2',
            'trading_balance' => 'decimal:2',
            'mining_balance' => 'decimal:2',
            'referral_balance' => 'decimal:2',
            'holding_balance' => 'decimal:2',
            'staking_balance' => 'decimal:2',
            'profit' => 'decimal:2',
        ];
    }

    public function fullname()
    {
        return $this->name;
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->status == 'active')
        {
            return '<span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-green-400 border border-green-100 dark:border-green-500">Active</span>';
        }
        if ($this->status == 'suspended')
        {
            return '<span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-red-400 border border-red-100 dark:border-red-500">Suspended</span>';
        }
        return '<span class="bg-orange-100 text-orange-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-md border border-orange-100 dark:bg-gray-700 dark:border-orange-300 dark:text-orange-300">InActive</span>';
    }

    /**
     * Check if user account is suspended
     */
    public function isSuspended()
    {
        return $this->status === 'suspended';
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by_id');
    }

    public function trades()
    {
        return $this->hasMany(Trade::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function withdrawal()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the user's plan subscriptions
     */
    public function userPlans()
    {
        return $this->hasMany(UserPlan::class);
    }

    /**
     * Get the user's active plan subscriptions
     */
    public function activeUserPlans()
    {
        return $this->hasMany(UserPlan::class)->where('status', 'active');
    }

    /**
     * Get the user's current trading plan subscription
     */
    public function currentTradingPlan()
    {
        return $this->hasMany(UserPlan::class)
            ->whereHas('plan', function($query) {
                $query->where('type', 'trading');
            })
            ->where('status', 'active')
            ->latest();
    }

    /**
     * Get the user's staking activities
     */
    public function stakings()
    {
        return $this->hasMany(UserStaking::class);
    }

    /**
     * Get the user's mining activities
     */
    public function minings()
    {
        return $this->hasMany(UserMining::class);
    }

    /**
     * Get total balance across all accounts
     */
    public function getTotalBalanceAttribute()
    {
        return $this->balance + $this->trading_balance + $this->mining_balance + $this->referral_balance + $this->holding_balance + $this->staking_balance + $this->profit;
    }

    /**
     * Get formatted balance for display
     */
    public function getFormattedBalanceAttribute()
    {
        return '$' . number_format($this->balance, 2);
    }

    public function getFormattedTradingBalanceAttribute()
    {
        return '$' . number_format($this->trading_balance, 2);
    }

    public function getFormattedMiningBalanceAttribute()
    {
        return '$' . number_format($this->mining_balance, 2);
    }

    public function getFormattedReferralBalanceAttribute()
    {
        return '$' . number_format($this->referral_balance, 2);
    }

    public function getFormattedHoldingBalanceAttribute()
    {
        return '$' . number_format($this->holding_balance, 2);
    }

    public function getFormattedStakingBalanceAttribute()
    {
        return '$' . number_format($this->staking_balance, 2);
    }

    public function getFormattedProfitAttribute()
    {
        return '$' . number_format($this->profit, 2);
    }

    public function balanceHistories()
    {
        return $this->hasMany(BalanceHistory::class);
    }

    /**
     * Get the user's avatar URL
     */
    public function getAvatarUrlAttribute()
    {
        if (!$this->avatar) {
            return asset('assets/img/avatar.svg');
        }
        
        // If avatar starts with 'files/', it's stored in storage
        if (str_starts_with($this->avatar, 'files/')) {
            return asset('storage/' . $this->avatar);
        }
        
        // Otherwise, treat it as a direct asset path
        return asset($this->avatar);
    }

    /**
     * Balance management methods
     */
    public function addToBalance($amount, $type = 'holding')
    {
        switch ($type) {
            case 'trading':
                $this->increment('trading_balance', $amount);
                break;
            case 'mining':
                $this->increment('mining_balance', $amount);
                break;
            case 'referral':
                $this->increment('referral_balance', $amount);
                break;
            case 'profit':
                $this->increment('profit', $amount);
                break;
            case 'holding':
            default:
                $this->increment('balance', $amount);
                break;
        }
    }

    public function deductFromBalance($amount, $type = 'holding')
    {
        switch ($type) {
            case 'trading':
                $this->decrement('trading_balance', $amount);
                break;
            case 'mining':
                $this->decrement('mining_balance', $amount);
                break;
            case 'referral':
                $this->decrement('referral_balance', $amount);
                break;
            case 'profit':
                $this->decrement('profit', $amount);
                break;
            case 'holding':
            default:
                $this->decrement('balance', $amount);
                break;
        }
    }

    /**
     * Check if user has sufficient balance
     */
    public function hasSufficientBalance($amount, $type = 'holding')
    {
        switch ($type) {
            case 'trading':
                return $this->trading_balance >= $amount;
            case 'mining':
                return $this->mining_balance >= $amount;
            case 'referral':
                return $this->referral_balance >= $amount;
            case 'profit':
                return $this->profit >= $amount;
            case 'holding':
            default:
                return $this->balance >= $amount;
        }
    }

    /**
     * Holding relationships
     */
    public function holdings()
    {
        return $this->hasMany(UserHolding::class);
    }

    public function holdingTransactions()
    {
        return $this->hasMany(HoldingTransaction::class);
    }

    public function fundTransfers()
    {
        return $this->hasMany(FundTransfer::class);
    }

    /**
     * Bot Trading relationships
     */
    public function botTradings()
    {
        return $this->hasMany(BotTrading::class);
    }

    public function botTrades()
    {
        return $this->hasMany(BotTrade::class);
    }

    /**
     * Copy Trading relationships
     */
    public function copiedTrades()
    {
        return $this->hasMany(CopiedTrade::class);
    }

    public function liveTrades()
    {
        return $this->hasMany(LiveTrade::class);
    }

    /**
     * Get AI Trader subscriptions for this user
     */
    public function aiTraderSubscriptions()
    {
        return $this->hasMany(AiTraderSubscription::class);
    }

    /**
     * Get active AI Trader subscriptions for this user
     */
    public function activeAiTraderSubscriptions()
    {
        return $this->hasMany(AiTraderSubscription::class)
            ->where('status', 'active')
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Get AI Trader activations for this user
     */
    public function aiTraderActivations()
    {
        return $this->hasMany(UserAiTrader::class);
    }

    /**
     * Check if user has active subscription to a specific AI Trader Plan
     */
    public function hasActiveAiTraderSubscription($planId)
    {
        return $this->activeAiTraderSubscriptions()
            ->where('ai_trader_plan_id', $planId)
            ->exists();
    }

    /**
     * Check if user has already activated a specific AI Trader
     */
    public function hasActivatedAiTrader($traderId)
    {
        return $this->aiTraderActivations()
            ->where('ai_trader_id', $traderId)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Get currency symbol for the user's preferred currency
     */
    public function getCurrencySymbolAttribute()
    {
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            'CAD' => 'C$',
            'AUD' => 'A$',
            'CHF' => 'CHF',
            'CNY' => '¥',
            'INR' => '₹',
            'BRL' => 'R$',
        ];

        return $symbols[$this->currency] ?? '$';
    }

    /**
     * Format amount with user's currency
     */
    public function formatAmount($amount, $decimals = 2)
    {
        return $this->currency_symbol . number_format($amount, $decimals);
    }

    /**
     * Get available currencies
     */
    public static function getAvailableCurrencies()
    {
        return [
            'USD' => 'USD',
            'EUR' => 'EUR',
            'GBP' => 'GBP',
            'JPY' => 'JPY',
            'CAD' => 'CAD',
            'AUD' => 'AUD',
            'CHF' => 'CHF',
            'CNY' => 'CNY',
            'INR' => 'INR',
            'BRL' => 'BRL',
        ];
    }

    /**
     * Create a notification for this user
     */
    public function createNotification($type, $title, $message, $data = [])
    {
        try {
            return \App\Models\UserNotification::create([
                'user_id' => $this->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create notification for user ' . $this->id . ': ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get the user's favorite assets
     */
    public function favorites()
    {
        return $this->belongsToMany(Asset::class, 'user_favorites')
            ->withTimestamps();
    }

    /**
     * Check if user has favorited an asset
     */
    public function hasFavorited($assetId)
    {
        return $this->favorites()->where('asset_id', $assetId)->exists();
    }
}
