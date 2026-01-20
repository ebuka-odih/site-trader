<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            // Drop the complex signal fields we added earlier
            $table->dropColumn([
                'signal_type',
                'entry_price_range_min',
                'entry_price_range_max',
                'stop_loss_percentage',
                'take_profit_percentage',
                'risk_reward_ratio',
                'signal_expiry_time',
                'trading_pairs',
                'timeframe',
                'confidence_level',
                'market_conditions',
                'signal_features',
                'signal_analysis_template',
                'includes_chart_analysis',
                'includes_tradingview_links',
                'includes_risk_management',
                'includes_market_updates',
                'max_signals_per_day',
                'signal_delivery_method'
            ]);

            // Add simplified, professional signal fields
            $table->string('signal_market_type')->nullable()->after('signal_duration'); // crypto, forex, stock, commodities
            $table->json('signal_pairs')->nullable()->after('signal_market_type'); // array of trading pairs
            $table->decimal('signal_leverage', 5, 2)->nullable()->after('signal_pairs'); // max leverage allowed
            $table->string('signal_expiry_duration')->nullable()->after('signal_leverage'); // 1h, 4h, 1d, 1w
            $table->json('signal_features')->nullable()->after('signal_expiry_duration'); // array of features like chart analysis, risk management, etc.
            $table->string('signal_delivery')->nullable()->after('signal_features'); // email, telegram, sms, push
            $table->integer('max_daily_signals')->nullable()->after('signal_delivery'); // max signals per day
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            // Re-add the old complex fields
            $table->string('signal_type')->nullable()->after('signal_duration');
            $table->decimal('entry_price_range_min', 15, 8)->nullable()->after('signal_type');
            $table->decimal('entry_price_range_max', 15, 8)->nullable()->after('entry_price_range_min');
            $table->decimal('stop_loss_percentage', 5, 2)->nullable()->after('entry_price_range_max');
            $table->decimal('take_profit_percentage', 5, 2)->nullable()->after('stop_loss_percentage');
            $table->decimal('risk_reward_ratio', 5, 2)->nullable()->after('take_profit_percentage');
            $table->string('signal_expiry_time')->nullable()->after('risk_reward_ratio');
            $table->string('trading_pairs')->nullable()->after('signal_expiry_time');
            $table->string('timeframe')->nullable()->after('trading_pairs');
            $table->decimal('confidence_level', 5, 2)->nullable()->after('timeframe');
            $table->string('market_conditions')->nullable()->after('confidence_level');
            $table->json('signal_features')->nullable()->after('market_conditions');
            $table->text('signal_analysis_template')->nullable()->after('signal_features');
            $table->boolean('includes_chart_analysis')->default(false)->after('signal_analysis_template');
            $table->boolean('includes_tradingview_links')->default(false)->after('includes_chart_analysis');
            $table->boolean('includes_risk_management')->default(false)->after('includes_tradingview_links');
            $table->boolean('includes_market_updates')->default(false)->after('includes_risk_management');
            $table->integer('max_signals_per_day')->nullable()->after('includes_market_updates');
            $table->string('signal_delivery_method')->nullable()->after('max_signals_per_day');

            // Drop the new simplified fields
            $table->dropColumn([
                'signal_market_type',
                'signal_pairs',
                'signal_leverage',
                'signal_expiry_duration',
                'signal_features',
                'signal_delivery',
                'max_daily_signals'
            ]);
        });
    }
};
