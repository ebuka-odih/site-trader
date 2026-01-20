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
        Schema::table('ai_trader_plans', function (Blueprint $table) {
            // Add new investment_amount column only if it doesn't exist
            if (!Schema::hasColumn('ai_trader_plans', 'investment_amount')) {
                $table->decimal('investment_amount', 10, 2)->default(0)->after('stocks_trading');
            }
            
            // Drop unnecessary columns only if they exist
            $columnsToDrop = [];
            if (Schema::hasColumn('ai_trader_plans', 'min_investment')) {
                $columnsToDrop[] = 'min_investment';
            }
            if (Schema::hasColumn('ai_trader_plans', 'max_investment')) {
                $columnsToDrop[] = 'max_investment';
            }
            if (Schema::hasColumn('ai_trader_plans', 'expected_return_percentage')) {
                $columnsToDrop[] = 'expected_return_percentage';
            }
            if (Schema::hasColumn('ai_trader_plans', 'trading_frequency_days')) {
                $columnsToDrop[] = 'trading_frequency_days';
            }
            if (Schema::hasColumn('ai_trader_plans', 'max_users')) {
                $columnsToDrop[] = 'max_users';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_trader_plans', function (Blueprint $table) {
            // Add back the dropped columns only if they don't exist
            if (!Schema::hasColumn('ai_trader_plans', 'min_investment')) {
                $table->decimal('min_investment', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('ai_trader_plans', 'max_investment')) {
                $table->decimal('max_investment', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('ai_trader_plans', 'expected_return_percentage')) {
                $table->decimal('expected_return_percentage', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('ai_trader_plans', 'trading_frequency_days')) {
                $table->integer('trading_frequency_days')->default(1);
            }
            if (!Schema::hasColumn('ai_trader_plans', 'max_users')) {
                $table->integer('max_users')->nullable();
            }
            
            // Drop the investment_amount column only if it exists
            if (Schema::hasColumn('ai_trader_plans', 'investment_amount')) {
                $table->dropColumn('investment_amount');
            }
        });
    }
};
