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
        Schema::create('bot_tradings', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Bot name
            $table->string('base_asset', 10); // e.g., BTC, ETH
            $table->string('quote_asset', 10); // e.g., USDT, USD
            $table->enum('strategy', ['grid', 'dca', 'scalping', 'trend_following']);
            $table->enum('status', ['active', 'paused', 'stopped'])->default('stopped');
            
            // Strategy Configuration
            $table->json('strategy_config'); // Strategy-specific settings
            
            // Risk Management
            $table->decimal('max_investment', 15, 2); // Maximum amount to invest
            $table->decimal('daily_loss_limit', 15, 2)->nullable(); // Daily loss limit
            $table->decimal('stop_loss_percentage', 5, 2)->nullable(); // Stop loss %
            $table->decimal('take_profit_percentage', 5, 2)->nullable(); // Take profit %
            
            // Trading Settings
            $table->decimal('min_trade_amount', 15, 2); // Minimum trade size
            $table->decimal('max_trade_amount', 15, 2); // Maximum trade size
            $table->integer('max_open_trades')->default(5); // Max concurrent trades
            
            // Time Settings
            $table->boolean('trading_24_7')->default(true);
            $table->time('trading_start_time')->nullable();
            $table->time('trading_end_time')->nullable();
            $table->json('trading_days')->nullable(); // Days of week to trade
            
            // Performance Tracking
            $table->decimal('total_invested', 15, 2)->default(0);
            $table->decimal('total_profit', 15, 2)->default(0);
            $table->integer('total_trades')->default(0);
            $table->integer('successful_trades')->default(0);
            $table->decimal('success_rate', 5, 2)->default(0); // Percentage
            
            // Bot Settings
            $table->boolean('auto_restart')->default(false);
            $table->timestamp('last_trade_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('stopped_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['base_asset', 'quote_asset'], 'bot_tradings_pair_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_tradings');
    }
};
