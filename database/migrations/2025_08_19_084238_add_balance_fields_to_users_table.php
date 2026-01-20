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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('trading_balance', 15, 2)->default(0.00)->after('balance');
            $table->decimal('mining_balance', 15, 2)->default(0.00)->after('trading_balance');
            $table->decimal('referral_balance', 15, 2)->default(0.00)->after('mining_balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['trading_balance', 'mining_balance', 'referral_balance']);
        });
    }
};
