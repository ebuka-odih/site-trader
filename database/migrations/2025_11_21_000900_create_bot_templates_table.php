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
        if (Schema::hasTable('bot_templates')) {
            return;
        }

        Schema::create('bot_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('trading_type', 40)->default('crypto');
            $table->string('base_asset', 10);
            $table->string('quote_asset', 10);
            $table->string('strategy', 80);
            $table->decimal('leverage', 8, 2)->default(1);
            $table->string('trade_duration', 10)->nullable();
            $table->decimal('target_yield_percentage', 8, 2)->nullable();
            $table->boolean('auto_close')->default(true);
            $table->json('strategy_config')->nullable();
            $table->decimal('max_investment', 15, 2);
            $table->decimal('daily_loss_limit', 15, 2)->nullable();
            $table->decimal('stop_loss_percentage', 8, 2)->nullable();
            $table->decimal('take_profit_percentage', 8, 2)->nullable();
            $table->decimal('min_trade_amount', 15, 2);
            $table->decimal('max_trade_amount', 15, 2);
            $table->integer('max_open_trades')->default(5);
            $table->boolean('trading_24_7')->default(true);
            $table->time('trading_start_time')->nullable();
            $table->time('trading_end_time')->nullable();
            $table->json('trading_days')->nullable();
            $table->boolean('auto_restart')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['strategy', 'trading_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_templates');
    }
};
