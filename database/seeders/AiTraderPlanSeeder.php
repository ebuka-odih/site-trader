<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AiTraderPlan;

class AiTraderPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks temporarily
        $driver = \DB::getDriverName();
        if ($driver === 'mysql') {
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } elseif ($driver === 'sqlite') {
            \DB::statement('PRAGMA foreign_keys = OFF;');
        }
        
        // Clear existing plans and related data
        // We need to delete in order due to foreign key constraints
        \App\Models\UserAiTrader::truncate();
        \App\Models\AiTraderSubscription::truncate();
        \App\Models\AiTrader::truncate();
        AiTraderPlan::truncate();
        
        // Re-enable foreign key checks
        if ($driver === 'mysql') {
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } elseif ($driver === 'sqlite') {
            \DB::statement('PRAGMA foreign_keys = ON;');
        }

        $plans = [
            [
                'name' => 'Starter AI Trader',
                'description' => 'Perfect for beginners looking to start their AI trading journey with proven strategies and moderate risk.',
                'price' => 99.99,
                'number_of_traders' => 5,
                'stocks_trading' => ['AAPL', 'MSFT', 'GOOGL', 'AMZN', 'TSLA'],
                'investment_amount' => 1000.00,
                'is_active' => true,
                'features' => [
                    '5 AI Traders Available',
                    'Basic Risk Management',
                    'Email Support',
                    'Monthly Performance Reports',
                    'Mobile App Access'
                ]
            ],
            [
                'name' => 'Professional AI Trader',
                'description' => 'Advanced AI trading strategies for experienced investors seeking higher returns with sophisticated risk management.',
                'price' => 299.99,
                'number_of_traders' => 5,
                'stocks_trading' => ['AAPL', 'MSFT', 'GOOGL', 'AMZN', 'TSLA', 'NVDA', 'META', 'NFLX', 'AMD', 'CRM'],
                'investment_amount' => 5000.00,
                'is_active' => true,
                'features' => [
                    '5 Advanced AI Traders',
                    'Advanced Risk Management',
                    'Priority Support',
                    'Weekly Performance Reports',
                    'Custom Trading Strategies',
                    'API Access',
                    'Portfolio Analytics'
                ]
            ],
            [
                'name' => 'Enterprise AI Trader',
                'description' => 'Premium AI trading solution for institutional investors and high-net-worth individuals with maximum returns.',
                'price' => 999.99,
                'number_of_traders' => 5,
                'stocks_trading' => ['AAPL', 'MSFT', 'GOOGL', 'AMZN', 'TSLA', 'NVDA', 'META', 'NFLX', 'AMD', 'CRM', 'ADBE', 'PYPL', 'INTC', 'CSCO', 'ORCL'],
                'investment_amount' => 25000.00,
                'is_active' => true,
                'features' => [
                    '5 Elite AI Traders',
                    'Maximum Risk Management',
                    '24/7 Dedicated Support',
                    'Real-time Performance Monitoring',
                    'Custom AI Model Training',
                    'Full API Access',
                    'Advanced Portfolio Analytics',
                    'White-label Solutions',
                    'Personal Account Manager'
                ]
            ]
        ];

        foreach ($plans as $planData) {
            AiTraderPlan::create($planData);
        }

        $this->command->info('AI Trader Plans seeded successfully!');
    }
}