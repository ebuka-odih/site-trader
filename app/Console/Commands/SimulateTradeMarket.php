<?php

namespace App\Console\Commands;

use App\Models\Trade;
use App\Models\TradePair;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SimulateTradeMarket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trade:simulate-market';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate trade market activities including price movements and trade executions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting trade market simulation...');
        
        try {
            // Simulate price movements for trade pairs
            $this->simulatePriceMovements();
            
            // Simulate some random trades
            $this->simulateRandomTrades();
            
            // Update existing trade P&L
            $this->updateTradePnL();
            
            $this->info('Trade market simulation completed successfully.');
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Trade simulation failed: ' . $e->getMessage());
            Log::error('Trade simulation failed: ' . $e->getMessage());
            return 1;
        }
    }
    
    /**
     * Simulate price movements for trade pairs
     */
    private function simulatePriceMovements()
    {
        $this->info('Simulating price movements...');
        
        $tradePairs = TradePair::all();
        $priceChanges = 0;
        
        foreach ($tradePairs as $pair) {
            // Generate random price movement (-5% to +5%)
            $changePercent = (rand(-50, 50) / 1000); // -5% to +5%
            $currentPrice = $pair->current_price ?? 100;
            $newPrice = $currentPrice * (1 + $changePercent);
            
            $pair->update([
                'current_price' => $newPrice,
                'price_change_24h' => $changePercent * 100,
                'updated_at' => now()
            ]);
            
            $priceChanges++;
        }
        
        $this->info("Updated prices for {$priceChanges} trade pairs.");
    }
    
    /**
     * Simulate random trades for demo purposes
     */
    private function simulateRandomTrades()
    {
        $this->info('Simulating random trades...');
        
        // Only create trades if we have users and trade pairs
        $users = User::take(5)->get();
        $tradePairs = TradePair::take(3)->get();
        
        if ($users->isEmpty() || $tradePairs->isEmpty()) {
            $this->info('No users or trade pairs found for simulation.');
            return;
        }
        
        $tradesCreated = 0;
        
        for ($i = 0; $i < rand(1, 3); $i++) {
            $user = $users->random();
            $pair = $tradePairs->random();
            $actionType = rand(0, 1) ? 'buy' : 'sell';
            $amount = rand(100, 1000);
            $leverage = rand(1, 10);
            
            try {
                Trade::create([
                    'user_id' => $user->id,
                    'trade_pair_id' => $pair->id,
                    'action_type' => $actionType,
                    'amount' => $amount,
                    'leverage' => $leverage,
                    'entry_price' => $pair->current_price,
                    'status' => 'open',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                $tradesCreated++;
                
            } catch (\Exception $e) {
                $this->warn("Failed to create trade: " . $e->getMessage());
            }
        }
        
        $this->info("Created {$tradesCreated} random trades.");
    }
    
    /**
     * Update P&L for existing open trades
     */
    private function updateTradePnL()
    {
        $this->info('Updating trade P&L...');
        
        $openTrades = Trade::where('status', 'open')->get();
        $updatedTrades = 0;
        
        foreach ($openTrades as $trade) {
            if ($trade->trade_pair && $trade->entry_price) {
                $currentPrice = $trade->trade_pair->current_price;
                $entryPrice = $trade->entry_price;
                
                if ($trade->action_type === 'buy') {
                    $profitLoss = (($currentPrice - $entryPrice) / $entryPrice) * $trade->amount * $trade->leverage;
                } else {
                    $profitLoss = (($entryPrice - $currentPrice) / $entryPrice) * $trade->amount * $trade->leverage;
                }
                
                $trade->update([
                    'profit_loss' => $profitLoss,
                    'updated_at' => now()
                ]);
                
                $updatedTrades++;
            }
        }
        
        $this->info("Updated P&L for {$updatedTrades} open trades.");
    }
}
