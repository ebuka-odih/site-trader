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
        Schema::create('trading_signals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('symbol'); // BTC/USDT, ETH/USDT, etc.
            $table->enum('type', ['buy', 'sell']);
            $table->enum('status', ['active', 'completed', 'cancelled', 'expired'])->default('active');
            $table->decimal('entry_price', 15, 8);
            $table->decimal('exit_price', 15, 8)->nullable();
            $table->decimal('stop_loss', 15, 8)->nullable();
            $table->decimal('take_profit', 15, 8)->nullable();
            $table->decimal('risk_reward_ratio', 5, 2)->nullable();
            $table->decimal('lot_size', 10, 4)->nullable();
            $table->decimal('leverage', 5, 2)->nullable();
            $table->integer('signal_strength')->default(3); // 1-5 stars
            $table->text('analysis')->nullable();
            $table->string('chart_image')->nullable();
            $table->string('tradingview_link')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('executed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->decimal('profit_loss', 15, 8)->nullable();
            $table->decimal('profit_loss_percentage', 5, 2)->nullable();
            $table->uuid('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('is_premium')->default(false);
            $table->json('tags')->nullable(); // array of tags
            $table->enum('market_conditions', ['bullish', 'bearish', 'sideways'])->nullable();
            $table->string('timeframe')->nullable(); // 1m, 5m, 15m, 1h, 4h, 1d
            $table->decimal('confidence_level', 5, 2)->nullable(); // 1-100%
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trading_signals');
    }
};
