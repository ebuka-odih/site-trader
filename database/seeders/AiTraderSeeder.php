<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AiTrader;
use App\Models\AiTraderPlan;

class AiTraderSeeder extends Seeder
{
    public function run(): void
    {
        // AiTrader::truncate(); // Already handled by AiTraderPlanSeeder

        $plans = AiTraderPlan::all();

        foreach ($plans as $plan) {
            $traders = $this->getTradersForPlan($plan->name);
            
            foreach ($traders as $traderData) {
                $traderData['ai_trader_plan_id'] = $plan->id;
                AiTrader::create($traderData);
            }
        }

        $this->command->info('AI Traders seeded successfully!');
    }

    private function getTradersForPlan($planName): array
    {
        $baseTraders = [
            [
                'name' => 'Conservative Growth Trader',
                'trading_strategy' => 'conservative',
                'ai_model' => 'GPT-4-Trader',
                'ai_confidence' => 'medium',
                'ai_learning_mode' => 'conservative',
                'stocks_to_trade' => ['AAPL', 'MSFT', 'GOOGL'],
                'risk_tolerance' => 0.3,
                'stop_loss_percentage' => 5.0,
                'take_profit_percentage' => 15.0,
                'max_positions' => 3,
                'position_size_percentage' => 25.0,
                'is_active' => true,
                'current_performance' => 8.5,
                'total_trades' => 45,
                'winning_trades' => 32,
                'win_rate' => 71.1
            ],
            [
                'name' => 'Balanced Portfolio Trader',
                'trading_strategy' => 'moderate',
                'ai_model' => 'Gemini-Pro-Trader',
                'ai_confidence' => 'medium',
                'ai_learning_mode' => 'adaptive',
                'stocks_to_trade' => ['AAPL', 'MSFT', 'AMZN', 'TSLA'],
                'risk_tolerance' => 0.5,
                'stop_loss_percentage' => 7.0,
                'take_profit_percentage' => 20.0,
                'max_positions' => 4,
                'position_size_percentage' => 20.0,
                'is_active' => true,
                'current_performance' => 12.3,
                'total_trades' => 38,
                'winning_trades' => 26,
                'win_rate' => 68.4
            ],
            [
                'name' => 'Tech Focus Trader',
                'trading_strategy' => 'moderate',
                'ai_model' => 'Claude-3.5-Sonnet-Trader',
                'ai_confidence' => 'high',
                'ai_learning_mode' => 'adaptive',
                'stocks_to_trade' => ['AAPL', 'MSFT', 'GOOGL', 'AMZN', 'TSLA'],
                'risk_tolerance' => 0.6,
                'stop_loss_percentage' => 8.0,
                'take_profit_percentage' => 25.0,
                'max_positions' => 5,
                'position_size_percentage' => 18.0,
                'is_active' => true,
                'current_performance' => 15.7,
                'total_trades' => 52,
                'winning_trades' => 35,
                'win_rate' => 67.3
            ],
            [
                'name' => 'Steady Income Trader',
                'trading_strategy' => 'conservative',
                'ai_model' => 'Alpha-Trader-X1',
                'ai_confidence' => 'medium',
                'ai_learning_mode' => 'conservative',
                'stocks_to_trade' => ['AAPL', 'MSFT', 'GOOGL'],
                'risk_tolerance' => 0.4,
                'stop_loss_percentage' => 6.0,
                'take_profit_percentage' => 18.0,
                'max_positions' => 3,
                'position_size_percentage' => 22.0,
                'is_active' => true,
                'current_performance' => 9.8,
                'total_trades' => 41,
                'winning_trades' => 30,
                'win_rate' => 73.2
            ],
            [
                'name' => 'Growth Opportunity Trader',
                'trading_strategy' => 'moderate',
                'ai_model' => 'Quantum-Trader-Pro',
                'ai_confidence' => 'high',
                'ai_learning_mode' => 'adaptive',
                'stocks_to_trade' => ['AAPL', 'MSFT', 'GOOGL', 'AMZN', 'TSLA'],
                'risk_tolerance' => 0.5,
                'stop_loss_percentage' => 7.5,
                'take_profit_percentage' => 22.0,
                'max_positions' => 4,
                'position_size_percentage' => 19.0,
                'is_active' => true,
                'current_performance' => 13.2,
                'total_trades' => 47,
                'winning_trades' => 31,
                'win_rate' => 66.0
            ]
        ];

        // Modify traders based on plan
        if ($planName === 'Professional AI Trader') {
            foreach ($baseTraders as &$trader) {
                $trader['name'] = 'Pro ' . $trader['name'];
                $trader['ai_model'] = 'GPT-4-Turbo-Trader';
                $trader['ai_confidence'] = 'high';
                $trader['stocks_to_trade'] = array_merge($trader['stocks_to_trade'], ['NVDA', 'META', 'NFLX', 'AMD', 'CRM']);
                $trader['risk_tolerance'] += 0.2;
                $trader['current_performance'] += 10;
                $trader['max_positions'] += 2;
            }
        } elseif ($planName === 'Enterprise AI Trader') {
            foreach ($baseTraders as &$trader) {
                $trader['name'] = 'Enterprise ' . $trader['name'];
                $trader['ai_model'] = 'Neural-Trader-Elite';
                $trader['ai_confidence'] = 'maximum';
                $trader['stocks_to_trade'] = array_merge($trader['stocks_to_trade'], ['NVDA', 'META', 'NFLX', 'AMD', 'CRM', 'ADBE', 'PYPL', 'INTC', 'CSCO', 'ORCL']);
                $trader['risk_tolerance'] += 0.3;
                $trader['current_performance'] += 20;
                $trader['max_positions'] += 5;
            }
        }

        return $baseTraders;
    }
}