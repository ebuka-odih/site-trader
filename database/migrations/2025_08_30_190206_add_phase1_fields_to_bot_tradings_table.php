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
        Schema::table('bot_tradings', function (Blueprint $table) {
            // Phase 1: Core Risk Management Fields
            $table->decimal('leverage', 5, 2)->default(1.00)->after('quote_asset'); // 1.00 = no leverage, 10.00 = 10x
            $table->string('trade_duration')->default('24h')->after('leverage'); // 1h, 4h, 24h, 1w, 1m
            $table->decimal('target_yield_percentage', 5, 2)->nullable()->after('trade_duration'); // e.g., 5.00 for 5%
            $table->boolean('auto_close')->default(true)->after('target_yield_percentage'); // Auto-close trades at duration
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bot_tradings', function (Blueprint $table) {
            $table->dropColumn([
                'leverage',
                'trade_duration', 
                'target_yield_percentage',
                'auto_close'
            ]);
        });
    }
};
