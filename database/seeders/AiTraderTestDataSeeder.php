<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AiTraderPlan;
use App\Models\AiTrader;

class AiTraderTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create AI Trader Plans
        $plans = [
            [
                'name' => 'Starter AI Trader',
                'description' => 'Perfect for beginners with conservative AI trading strategies',
                'price' => 99.99,
                'number_of_traders' => 1,
                'stocks_trading' => ['AAPL', 'GOOGL', 'MSFT'],
                'investment_amount' => 1000.00,
                'features' => ['Basic AI Analysis', 'Email Support', 'Daily Reports'],
                'is_active' => true,
            ],
            [
                'name' => 'Professional AI Trader',
                'description' => 'Advanced AI trading with multiple strategies and comprehensive analysis',
                'price' => 299.99,
                'number_of_traders' => 3,
                'stocks_trading' => ['AAPL', 'GOOGL', 'MSFT', 'TSLA', 'AMZN', 'NVDA'],
                'investment_amount' => 5000.00,
                'features' => ['Advanced AI Analysis', 'Priority Support', 'Real-time Reports', 'Custom Strategies'],
                'is_active' => true,
            ],
            [
                'name' => 'Elite AI Trader',
                'description' => 'Premium AI trading with cutting-edge models and maximum performance',
                'price' => 599.99,
                'number_of_traders' => 5,
                'stocks_trading' => ['AAPL', 'GOOGL', 'MSFT', 'TSLA', 'AMZN', 'NVDA', 'META', 'NFLX', 'AMD', 'INTC'],
                'investment_amount' => 10000.00,
                'features' => ['Premium AI Models', '24/7 Support', 'Live Trading Dashboard', 'Custom AI Training', 'Advanced Analytics'],
                'is_active' => true,
            ],
            [
                'name' => 'Enterprise AI Trader',
                'description' => 'Enterprise-grade AI trading solution for institutional investors',
                'price' => 1299.99,
                'number_of_traders' => 10,
                'stocks_trading' => ['AAPL', 'GOOGL', 'MSFT', 'TSLA', 'AMZN', 'NVDA', 'META', 'NFLX', 'AMD', 'INTC', 'CRM', 'ADBE', 'PYPL', 'UBER', 'SPOT'],
                'investment_amount' => 25000.00,
                'features' => ['Enterprise AI Models', 'Dedicated Support', 'Custom Integration', 'White-label Solution', 'Advanced Risk Management'],
                'is_active' => true,
            ]
        ];

        foreach ($plans as $planData) {
            AiTraderPlan::create($planData);
        }

        // Get the created plans
        $starterPlan = AiTraderPlan::where('name', 'Starter AI Trader')->first();
        $professionalPlan = AiTraderPlan::where('name', 'Professional AI Trader')->first();
        $elitePlan = AiTraderPlan::where('name', 'Elite AI Trader')->first();
        $enterprisePlan = AiTraderPlan::where('name', 'Enterprise AI Trader')->first();

        // Create AI Traders
        $traders = [
            // Starter Plan Traders
            [
                'ai_trader_plan_id' => $starterPlan->id,
                'name' => 'Conservative Trader Alpha',
                'trading_strategy' => 'conservative',
                'ai_model' => 'GPT-4o',
                'ai_confidence' => 'medium',
                'ai_learning_mode' => 'conservative',
                'stocks_to_trade' => ['AAPL', 'GOOGL'],
                'risk_tolerance' => 0.3,
                'stop_loss_percentage' => 3.0,
                'take_profit_percentage' => 8.0,
                'max_positions' => 2,
                'position_size_percentage' => 15.0,
                'is_active' => true,
                'current_performance' => 12.5,
                'total_trades' => 45,
                'winning_trades' => 28,
                'win_rate' => 62.2,
            ],

            // Professional Plan Traders
            [
                'ai_trader_plan_id' => $professionalPlan->id,
                'name' => 'Balanced Trader Beta',
                'trading_strategy' => 'moderate',
                'ai_model' => 'Claude 3.5 Sonnet',
                'ai_confidence' => 'high',
                'ai_learning_mode' => 'adaptive',
                'stocks_to_trade' => ['AAPL', 'GOOGL', 'MSFT', 'TSLA'],
                'risk_tolerance' => 0.5,
                'stop_loss_percentage' => 4.0,
                'take_profit_percentage' => 12.0,
                'max_positions' => 4,
                'position_size_percentage' => 20.0,
                'is_active' => true,
                'current_performance' => 18.7,
                'total_trades' => 78,
                'winning_trades' => 52,
                'win_rate' => 66.7,
            ],
            [
                'ai_trader_plan_id' => $professionalPlan->id,
                'name' => 'Tech Focus Trader Gamma',
                'trading_strategy' => 'aggressive',
                'ai_model' => 'GPT-4 Turbo',
                'ai_confidence' => 'high',
                'ai_learning_mode' => 'aggressive',
                'stocks_to_trade' => ['NVDA', 'AMD', 'INTC', 'TSLA'],
                'risk_tolerance' => 0.7,
                'stop_loss_percentage' => 5.0,
                'take_profit_percentage' => 15.0,
                'max_positions' => 3,
                'position_size_percentage' => 25.0,
                'is_active' => true,
                'current_performance' => 24.3,
                'total_trades' => 62,
                'winning_trades' => 38,
                'win_rate' => 61.3,
            ],

            // Elite Plan Traders
            [
                'ai_trader_plan_id' => $elitePlan->id,
                'name' => 'Premium Growth Trader',
                'trading_strategy' => 'swing',
                'ai_model' => 'Gemini Ultra',
                'ai_confidence' => 'maximum',
                'ai_learning_mode' => 'adaptive',
                'stocks_to_trade' => ['AAPL', 'GOOGL', 'MSFT', 'AMZN', 'NVDA'],
                'risk_tolerance' => 0.6,
                'stop_loss_percentage' => 4.5,
                'take_profit_percentage' => 18.0,
                'max_positions' => 5,
                'position_size_percentage' => 18.0,
                'is_active' => true,
                'current_performance' => 31.2,
                'total_trades' => 95,
                'winning_trades' => 67,
                'win_rate' => 70.5,
            ],
            [
                'ai_trader_plan_id' => $elitePlan->id,
                'name' => 'High Frequency Trader',
                'trading_strategy' => 'scalping',
                'ai_model' => 'Llama 3.1 405B',
                'ai_confidence' => 'high',
                'ai_learning_mode' => 'experimental',
                'stocks_to_trade' => ['TSLA', 'NVDA', 'AMD', 'META', 'NFLX'],
                'risk_tolerance' => 0.8,
                'stop_loss_percentage' => 2.0,
                'take_profit_percentage' => 6.0,
                'max_positions' => 8,
                'position_size_percentage' => 12.0,
                'is_active' => true,
                'current_performance' => 28.9,
                'total_trades' => 156,
                'winning_trades' => 98,
                'win_rate' => 62.8,
            ],
            [
                'ai_trader_plan_id' => $elitePlan->id,
                'name' => 'Market Leader Trader',
                'trading_strategy' => 'day_trading',
                'ai_model' => 'Mixtral 8x22B',
                'ai_confidence' => 'maximum',
                'ai_learning_mode' => 'aggressive',
                'stocks_to_trade' => ['AAPL', 'MSFT', 'GOOGL', 'AMZN', 'TSLA'],
                'risk_tolerance' => 0.7,
                'stop_loss_percentage' => 3.5,
                'take_profit_percentage' => 10.0,
                'max_positions' => 6,
                'position_size_percentage' => 16.0,
                'is_active' => true,
                'current_performance' => 22.1,
                'total_trades' => 112,
                'winning_trades' => 74,
                'win_rate' => 66.1,
            ],

            // Enterprise Plan Traders
            [
                'ai_trader_plan_id' => $enterprisePlan->id,
                'name' => 'Institutional Alpha Trader',
                'trading_strategy' => 'conservative',
                'ai_model' => 'GPT-4o',
                'ai_confidence' => 'maximum',
                'ai_learning_mode' => 'conservative',
                'stocks_to_trade' => ['AAPL', 'GOOGL', 'MSFT', 'AMZN', 'NVDA', 'META'],
                'risk_tolerance' => 0.4,
                'stop_loss_percentage' => 2.5,
                'take_profit_percentage' => 8.0,
                'max_positions' => 10,
                'position_size_percentage' => 10.0,
                'is_active' => true,
                'current_performance' => 19.8,
                'total_trades' => 203,
                'winning_trades' => 142,
                'win_rate' => 69.9,
            ],
            [
                'ai_trader_plan_id' => $enterprisePlan->id,
                'name' => 'Portfolio Optimizer',
                'trading_strategy' => 'moderate',
                'ai_model' => 'Claude 3.5 Sonnet',
                'ai_confidence' => 'high',
                'ai_learning_mode' => 'adaptive',
                'stocks_to_trade' => ['AAPL', 'GOOGL', 'MSFT', 'TSLA', 'AMZN', 'NVDA', 'META', 'NFLX'],
                'risk_tolerance' => 0.5,
                'stop_loss_percentage' => 3.0,
                'take_profit_percentage' => 12.0,
                'max_positions' => 8,
                'position_size_percentage' => 12.0,
                'is_active' => true,
                'current_performance' => 26.4,
                'total_trades' => 178,
                'winning_trades' => 118,
                'win_rate' => 66.3,
            ],
            [
                'ai_trader_plan_id' => $enterprisePlan->id,
                'name' => 'Advanced Momentum Trader',
                'trading_strategy' => 'aggressive',
                'ai_model' => 'Gemini Ultra',
                'ai_confidence' => 'maximum',
                'ai_learning_mode' => 'experimental',
                'stocks_to_trade' => ['TSLA', 'NVDA', 'AMD', 'META', 'NFLX', 'CRM', 'ADBE'],
                'risk_tolerance' => 0.8,
                'stop_loss_percentage' => 4.0,
                'take_profit_percentage' => 16.0,
                'max_positions' => 7,
                'position_size_percentage' => 14.0,
                'is_active' => true,
                'current_performance' => 35.7,
                'total_trades' => 134,
                'winning_trades' => 89,
                'win_rate' => 66.4,
            ],
            [
                'ai_trader_plan_id' => $enterprisePlan->id,
                'name' => 'Market Neutral Trader',
                'trading_strategy' => 'swing',
                'ai_model' => 'Llama 3.1 405B',
                'ai_confidence' => 'high',
                'ai_learning_mode' => 'adaptive',
                'stocks_to_trade' => ['AAPL', 'MSFT', 'GOOGL', 'AMZN', 'NVDA', 'META', 'NFLX', 'AMD', 'INTC'],
                'risk_tolerance' => 0.6,
                'stop_loss_percentage' => 3.5,
                'take_profit_percentage' => 14.0,
                'max_positions' => 9,
                'position_size_percentage' => 11.0,
                'is_active' => true,
                'current_performance' => 29.3,
                'total_trades' => 167,
                'winning_trades' => 108,
                'win_rate' => 64.7,
            ],
            [
                'ai_trader_plan_id' => $enterprisePlan->id,
                'name' => 'Risk-Adjusted Trader',
                'trading_strategy' => 'conservative',
                'ai_model' => 'Mixtral 8x22B',
                'ai_confidence' => 'medium',
                'ai_learning_mode' => 'conservative',
                'stocks_to_trade' => ['AAPL', 'GOOGL', 'MSFT', 'AMZN', 'NVDA', 'META', 'NFLX', 'AMD', 'INTC', 'CRM'],
                'risk_tolerance' => 0.3,
                'stop_loss_percentage' => 2.0,
                'take_profit_percentage' => 6.0,
                'max_positions' => 10,
                'position_size_percentage' => 10.0,
                'is_active' => true,
                'current_performance' => 16.2,
                'total_trades' => 189,
                'winning_trades' => 134,
                'win_rate' => 70.9,
            ]
        ];

        foreach ($traders as $traderData) {
            AiTrader::create($traderData);
        }

        $this->command->info('AI Trader test data created successfully!');
        $this->command->info('Created ' . count($plans) . ' AI Trader Plans');
        $this->command->info('Created ' . count($traders) . ' AI Traders');
    }
}