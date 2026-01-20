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
        Schema::create('bot_trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_trading_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            
            // Trade Details
            $table->string('trade_id')->unique(); // Unique trade identifier
            $table->enum('type', ['buy', 'sell']);
            $table->string('base_asset', 10); // e.g., BTC
            $table->string('quote_asset', 10); // e.g., USDT
            
            // Trade Amounts
            $table->decimal('base_amount', 20, 8); // Amount of base asset
            $table->decimal('quote_amount', 15, 2); // Amount of quote asset
            $table->decimal('price', 15, 8); // Price at which trade was executed
            
            // Trade Status
            $table->enum('status', ['pending', 'executed', 'cancelled', 'failed'])->default('pending');
            $table->enum('execution_type', ['market', 'limit'])->default('market');
            
            // Related Trade (for buy/sell pairs)
            $table->foreignId('related_trade_id')->nullable()->constrained('bot_trades')->onDelete('set null');
            
            // Profit/Loss (for completed trades)
            $table->decimal('profit_loss', 15, 2)->nullable();
            $table->decimal('profit_loss_percentage', 8, 4)->nullable();
            
            // Timestamps
            $table->timestamp('executed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            // Additional Data
            $table->json('metadata')->nullable(); // Additional trade data
            $table->text('notes')->nullable(); // User notes
            
            $table->timestamps();
            
            // Indexes
            $table->index(['bot_trading_id', 'status']);
            $table->index(['user_id', 'created_at']);
            $table->index(['base_asset', 'quote_asset'], 'bot_trades_pair_index');
            $table->index('executed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_trades');
    }
};
