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
        Schema::create('ai_traders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_trader_plan_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('trading_strategy', ['conservative', 'moderate', 'aggressive', 'scalping', 'swing', 'day_trading']);
            $table->decimal('risk_tolerance', 3, 2)->default(0.5); // 0.0 to 1.0
            $table->decimal('stop_loss_percentage', 5, 2)->default(5.0);
            $table->decimal('take_profit_percentage', 5, 2)->default(10.0);
            $table->integer('max_positions')->default(5);
            $table->decimal('position_size_percentage', 5, 2)->default(20.0); // % of portfolio per trade
            $table->json('technical_indicators'); // RSI, MACD, Moving Averages, etc.
            $table->json('market_conditions'); // Bull, Bear, Sideways market preferences
            $table->time('trading_start_time')->default('09:30:00');
            $table->time('trading_end_time')->default('16:00:00');
            $table->json('trading_days'); // Days of week to trade [1,2,3,4,5] for weekdays
            $table->boolean('news_sentiment_analysis')->default(true);
            $table->boolean('volume_analysis')->default(true);
            $table->boolean('price_action_analysis')->default(true);
            $table->decimal('min_volume_threshold', 15, 2)->nullable();
            $table->decimal('max_volume_threshold', 15, 2)->nullable();
            $table->json('sector_preferences')->nullable(); // Technology, Healthcare, Finance, etc.
            $table->json('market_cap_preferences')->nullable(); // Large, Mid, Small cap
            $table->boolean('is_active')->default(true);
            $table->decimal('current_performance', 8, 4)->default(0.0); // Current ROI
            $table->integer('total_trades')->default(0);
            $table->integer('winning_trades')->default(0);
            $table->decimal('win_rate', 5, 2)->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_traders');
    }
};
