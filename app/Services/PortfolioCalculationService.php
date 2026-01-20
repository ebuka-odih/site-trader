<?php

namespace App\Services;

use App\Models\User;
use App\Models\Asset;
use App\Models\UserHolding;
use App\Models\HoldingTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PortfolioCalculationService
{
    public function updateUserPortfolio(User $user): void
    {
        try {
            $holdings = $user->holdings()->with('asset')->get();
            
            foreach ($holdings as $holding) {
                $this->updateHoldingValues($holding);
            }
            
            // Update user's holding balance (total value of all holdings)
            $totalHoldingValue = $holdings->sum('current_value');
            $user->update(['holding_balance' => $totalHoldingValue]);
            
            Log::info('Portfolio updated for user', [
                'user_id' => $user->id,
                'total_value' => $totalHoldingValue,
                'holdings_count' => $holdings->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update user portfolio: ' . $e->getMessage(), [
                'user_id' => $user->id
            ]);
        }
    }

    public function updateHoldingValues(UserHolding $holding): void
    {
        try {
            $currentPrice = $holding->asset->current_price;
            $currentValue = $holding->quantity * $currentPrice;
            $unrealizedPnl = $currentValue - $holding->total_invested;
            $unrealizedPnlPercentage = $holding->total_invested > 0 
                ? ($unrealizedPnl / $holding->total_invested) * 100 
                : 0;

            $holding->update([
                'current_value' => $currentValue,
                'unrealized_pnl' => $unrealizedPnl,
                'unrealized_pnl_percentage' => $unrealizedPnlPercentage,
                'last_updated' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update holding values: ' . $e->getMessage(), [
                'holding_id' => $holding->id
            ]);
        }
    }

    public function buyAsset(User $user, Asset $asset, float $quantity, float $pricePerUnit): UserHolding
    {
        return DB::transaction(function () use ($user, $asset, $quantity, $pricePerUnit) {
            $totalAmount = $quantity * $pricePerUnit;
            
            // Check if user has enough balance (use main balance, not holding balance)
            if ($user->balance < $totalAmount) {
                throw new \Exception('Insufficient balance. Required: $' . number_format($totalAmount, 2) . ', Available: $' . number_format($user->balance, 2));
            }
            
            // Deduct from user's main balance
            $user->decrement('balance', $totalAmount);
            
            // Find existing holding or create new one
            $holding = UserHolding::firstOrNew([
                'user_id' => $user->id,
                'asset_id' => $asset->id,
            ]);
            
            if ($holding->exists) {
                // Update existing holding
                $newQuantity = $holding->quantity + $quantity;
                $newTotalInvested = $holding->total_invested + $totalAmount;
                $newAveragePrice = $newTotalInvested / $newQuantity;
                
                $holding->update([
                    'quantity' => $newQuantity,
                    'average_buy_price' => $newAveragePrice,
                    'total_invested' => $newTotalInvested,
                ]);
            } else {
                // Create new holding
                $holding->fill([
                    'quantity' => $quantity,
                    'average_buy_price' => $pricePerUnit,
                    'total_invested' => $totalAmount,
                ]);
                $holding->save();
            }
            
            // Create transaction record
            $holding->transactions()->create([
                'user_id' => $user->id,
                'asset_id' => $asset->id,
                'type' => 'buy',
                'quantity' => $quantity,
                'price_per_unit' => $pricePerUnit,
                'total_amount' => $totalAmount,
                'status' => 'completed',
            ]);
            
            // Update current values
            $this->updateHoldingValues($holding);
            
            Log::info('Asset purchased successfully', [
                'user_id' => $user->id,
                'asset_id' => $asset->id,
                'quantity' => $quantity,
                'price_per_unit' => $pricePerUnit,
                'total_amount' => $totalAmount
            ]);
            
            return $holding;
        });
    }

    public function sellAsset(User $user, Asset $asset, float $quantity, float $pricePerUnit): UserHolding
    {
        return DB::transaction(function () use ($user, $asset, $quantity, $pricePerUnit) {
            $holding = UserHolding::where('user_id', $user->id)
                ->where('asset_id', $asset->id)
                ->firstOrFail();
            
            if ($holding->quantity < $quantity) {
                throw new \Exception('Insufficient quantity to sell. Available: ' . $holding->quantity . ', Requested: ' . $quantity);
            }
            
            $totalAmount = $quantity * $pricePerUnit;
            
            // Add to user's trading balance
            $user->increment('trading_balance', $totalAmount);
            
            // Update holding
            $newQuantity = $holding->quantity - $quantity;
            
            // Create transaction record first
            HoldingTransaction::create([
                'user_id' => $user->id,
                'asset_id' => $asset->id,
                'type' => 'sell',
                'quantity' => $quantity,
                'price_per_unit' => $pricePerUnit,
                'total_amount' => $totalAmount,
                'status' => 'completed',
            ]);
            
            if ($newQuantity > 0) {
                $holding->update(['quantity' => $newQuantity]);
                $result = $holding;
            } else {
                $result = $holding;
                $holding->delete();
            }
            
            Log::info('Asset sold successfully', [
                'user_id' => $user->id,
                'asset_id' => $asset->id,
                'quantity' => $quantity,
                'price_per_unit' => $pricePerUnit,
                'total_amount' => $totalAmount
            ]);
            
            return $result;
        });
    }

    public function getPortfolioSummary(User $user): array
    {
        $holdings = $user->holdings()->with('asset')->get();
        
        $totalValue = $holdings->sum('current_value');
        $totalInvested = $holdings->sum('total_invested');
        $totalPnl = $totalValue - $totalInvested;
        $totalPnlPercentage = $totalInvested > 0 ? ($totalPnl / $totalInvested) * 100 : 0;
        
        return [
            'total_value' => $totalValue,
            'total_invested' => $totalInvested,
            'total_pnl' => $totalPnl,
            'total_pnl_percentage' => $totalPnlPercentage,
            'holdings_count' => $holdings->count(),
            'holdings' => $holdings
        ];
    }
}
