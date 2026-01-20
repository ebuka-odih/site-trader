<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AiTrader;
use App\Models\UserAiTrader;

class AiTraderManagementTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user and some traders
        $user = User::first();
        $traders = AiTrader::take(3)->get();

        if (!$user || $traders->isEmpty()) {
            $this->command->error('No users or traders found. Please run the main seeders first.');
            return;
        }

        foreach ($traders as $trader) {
            // Check if activation already exists
            $existingActivation = UserAiTrader::where('user_id', $user->id)
                ->where('ai_trader_id', $trader->id)
                ->first();

            if (!$existingActivation) {
                UserAiTrader::create([
                    'user_id' => $user->id,
                    'ai_trader_id' => $trader->id,
                    'ai_trader_plan_id' => $trader->ai_trader_plan_id,
                    'investment_amount' => rand(1000, 10000),
                    'status' => 'active',
                    'activated_at' => now()->subDays(rand(1, 30)),
                    'current_balance' => rand(1000, 12000),
                    'total_profit_loss' => rand(-500, 2000),
                    'total_trades_executed' => rand(5, 50),
                    'winning_trades' => rand(2, 30),
                    'win_rate' => rand(40, 80)
                ]);
            }
        }

        $this->command->info('AI Trader Management test data created successfully!');
        
        // Show stats
        $totalActive = UserAiTrader::where('status', 'active')->count();
        $totalInvestment = UserAiTrader::where('status', 'active')->sum('investment_amount');
        $totalProfitLoss = UserAiTrader::where('status', 'active')->sum('total_profit_loss');

        $this->command->info("Total Active Traders: {$totalActive}");
        $this->command->info("Total Investment: $" . number_format($totalInvestment, 2));
        $this->command->info("Total P&L: $" . number_format($totalProfitLoss, 2));
    }
    
}