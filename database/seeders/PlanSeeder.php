<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing plans (use delete instead of truncate to avoid foreign key issues)
        Plan::query()->delete();

        // Trading Plans
        Plan::create([
            'name' => 'Starter Trading',
            'type' => 'trading',
            'description' => 'Perfect for beginners starting their trading journey with essential features',
            'price' => 99.00,
            'original_price' => 149.00,
            'min_funding' => 100.00,
            'max_funding' => 1000.00,
            'currency' => 'USD',
            'is_active' => true,
            'sort_order' => 1,
            'pairs' => '50+ Trading Pairs',
            'leverage' => 100.00,
            'spreads' => 1.5,
            'swap_fees' => 2.5,
            'max_lot_size' => '10 lots',
        ]);

        Plan::create([
            'name' => 'Professional Trading',
            'type' => 'trading',
            'description' => 'Advanced features for experienced traders with enhanced capabilities',
            'price' => 299.00,
            'original_price' => 399.00,
            'min_funding' => 500.00,
            'max_funding' => 5000.00,
            'currency' => 'USD',
            'is_active' => true,
            'sort_order' => 2,
            'pairs' => '100+ Trading Pairs',
            'leverage' => 200.00,
            'spreads' => 0.8,
            'swap_fees' => 1.5,
            'max_lot_size' => '50 lots',
        ]);

        Plan::create([
            'name' => 'Premium Trading',
            'type' => 'trading',
            'description' => 'Ultimate trading experience with maximum benefits and features',
            'price' => 599.00,
            'original_price' => 799.00,
            'min_funding' => 1000.00,
            'max_funding' => 10000.00,
            'currency' => 'USD',
            'is_active' => true,
            'sort_order' => 3,
            'pairs' => '200+ Trading Pairs',
            'leverage' => 500.00,
            'spreads' => 0.3,
            'swap_fees' => 0.5,
            'max_lot_size' => '100 lots',
        ]);

        // Signal Plans
        Plan::create([
            'name' => 'Basic Signal Plan',
            'type' => 'signal',
            'description' => 'Essential trading signals for beginners with basic market analysis',
            'price' => 49.00,
            'original_price' => 79.00,
            'min_funding' => 49.00,
            'currency' => 'USD',
            'is_active' => true,
            'sort_order' => 1,
            'signal_strength' => 3,
            'signal_quantity' => 30,
            'signal_duration' => 30,
            'daily_signals' => 1,
            'success_rate' => 75.0,
            'signal_market_type' => 'crypto',
            'signal_pairs' => [],
            'signal_features' => ['Real-time Alerts', 'Entry/Exit Points', 'Stop Loss Levels', 'Basic Chart Analysis'],
            'max_daily_signals' => 1,
        ]);

        Plan::create([
            'name' => 'Advanced Signal Plan',
            'type' => 'signal',
            'description' => 'Professional trading signals with detailed analysis and higher accuracy',
            'price' => 149.00,
            'original_price' => 199.00,
            'min_funding' => 149.00,
            'currency' => 'USD',
            'is_active' => true,
            'sort_order' => 2,
            'signal_strength' => 4,
            'signal_quantity' => 80,
            'signal_duration' => 30,
            'daily_signals' => 3,
            'success_rate' => 88.0,
            'signal_market_type' => 'crypto',
            'signal_pairs' => [],
            'signal_features' => ['Real-time Alerts', 'Entry/Exit Points', 'Stop Loss Levels', 'Take Profit Targets', 'Risk Management', 'Technical Analysis', 'Chart Patterns', 'TradingView Links'],
            'max_daily_signals' => 3,
        ]);

        Plan::create([
            'name' => 'Premium Signal Plan',
            'type' => 'signal',
            'description' => 'Elite signal service with maximum accuracy and real-time alerts',
            'price' => 299.00,
            'original_price' => 399.00,
            'min_funding' => 299.00,
            'currency' => 'USD',
            'is_active' => true,
            'sort_order' => 3,
            'signal_strength' => 4,
            'signal_quantity' => 150,
            'signal_duration' => 30,
            'daily_signals' => 5,
            'success_rate' => 95.0,
            'signal_market_type' => 'crypto',
            'signal_pairs' => [],
            'signal_features' => ['Real-time Alerts', 'Entry/Exit Points', 'Stop Loss Levels', 'Take Profit Targets', 'Advanced Risk Management', 'Premium Technical Analysis', 'Chart Patterns', 'TradingView Links', 'Market Sentiment Analysis', 'Portfolio Tracking', 'News Impact Alerts'],
            'max_daily_signals' => 5,
        ]);

        // Mining Plans
        Plan::create([
            'name' => 'Starter Mining',
            'type' => 'mining',
            'description' => 'Entry-level mining solution perfect for beginners',
            'price' => 199.00,
            'original_price' => 299.00,
            'min_funding' => 200.00,
            'max_funding' => 2000.00,
            'currency' => 'USD',
            'is_active' => true,
            'sort_order' => 1,
            'hashrate' => '100 TH/s',
            'equipment' => '1 Antminer S19',
            'downtime' => '99.9% Uptime',
            'electricity_costs' => 'Included',
            'mining_duration' => 30,
        ]);

        Plan::create([
            'name' => 'Professional Mining',
            'type' => 'mining',
            'description' => 'Advanced mining solution for serious miners',
            'price' => 499.00,
            'original_price' => 699.00,
            'min_funding' => 500.00,
            'max_funding' => 5000.00,
            'currency' => 'USD',
            'is_active' => true,
            'sort_order' => 2,
            'hashrate' => '500 TH/s',
            'equipment' => '5 Antminer S19',
            'downtime' => '99.95% Uptime',
            'electricity_costs' => 'Included',
            'mining_duration' => 30,
        ]);

        Plan::create([
            'name' => 'Enterprise Mining',
            'type' => 'mining',
            'description' => 'Large-scale mining operation for institutional clients',
            'price' => 999.00,
            'original_price' => 1499.00,
            'min_funding' => 1000.00,
            'max_funding' => null, // Unlimited
            'currency' => 'USD',
            'is_active' => true,
            'sort_order' => 3,
            'hashrate' => '1000 TH/s',
            'equipment' => '10 Antminer S19',
            'downtime' => '99.99% Uptime',
            'electricity_costs' => 'Included',
            'mining_duration' => 30,
        ]);

        // Staking Plans
        Plan::create([
            'name' => 'Basic Staking',
            'type' => 'staking',
            'description' => 'Simple staking solution for beginners with steady returns',
            'price' => 79.00,
            'original_price' => 119.00,
            'min_funding' => 100.00,
            'max_funding' => 1000.00,
            'currency' => 'USD',
            'is_active' => true,
            'sort_order' => 1,
            'apy_rate' => 8.0,
            'minimum_amount' => 100.00,
            'reward_frequency' => 'Monthly',
            'lock_period' => 7,
            'staking_duration' => 90,
        ]);

        Plan::create([
            'name' => 'Advanced Staking',
            'type' => 'staking',
            'description' => 'Enhanced staking with better returns and flexible options',
            'price' => 199.00,
            'original_price' => 299.00,
            'min_funding' => 250.00,
            'max_funding' => 2500.00,
            'currency' => 'USD',
            'is_active' => true,
            'sort_order' => 2,
            'apy_rate' => 12.5,
            'minimum_amount' => 250.00,
            'reward_frequency' => 'Weekly',
            'lock_period' => 15,
            'staking_duration' => 180,
        ]);

        Plan::create([
            'name' => 'Premium Staking',
            'type' => 'staking',
            'description' => 'Maximum staking returns with premium features and support',
            'price' => 399.00,
            'original_price' => 599.00,
            'min_funding' => 500.00,
            'max_funding' => 5000.00,
            'currency' => 'USD',
            'is_active' => true,
            'sort_order' => 3,
            'apy_rate' => 18.0,
            'minimum_amount' => 500.00,
            'reward_frequency' => 'Daily',
            'lock_period' => 30,
            'staking_duration' => 365,
        ]);
    }
}
