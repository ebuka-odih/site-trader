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
        Schema::create('live_trades', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id');
            $table->string('asset_type', 10); // crypto, stock, forex
            $table->string('symbol', 20); // BTC/USDT, AAPL, EUR/USD
            $table->string('order_type', 10); // limit, market
            $table->string('side', 10); // buy, sell
            $table->decimal('quantity', 18, 8)->nullable(); // For limit orders
            $table->decimal('price', 18, 8)->nullable(); // For limit orders
            $table->decimal('amount', 18, 8); // Total USD amount
            $table->decimal('leverage', 5, 2)->default(1.00);
            $table->string('status', 20)->default('pending'); // pending, filled, cancelled, rejected
            $table->timestamp('filled_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'status']);
            $table->index(['asset_type', 'symbol'], 'live_trades_asset_type_symbol_index', 'btree', 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_trades');
    }
};
