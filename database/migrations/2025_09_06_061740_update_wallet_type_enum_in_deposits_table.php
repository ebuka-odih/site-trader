<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            // Drop the existing wallet_type column
            $table->dropColumn('wallet_type');
        });
        
        Schema::table('deposits', function (Blueprint $table) {
            // Add the wallet_type column with the new ENUM values
            $table->enum('wallet_type', ['balance', 'trading', 'holding', 'staking'])->nullable()->after('payment_method_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            // Drop the wallet_type column
            $table->dropColumn('wallet_type');
        });
        
        Schema::table('deposits', function (Blueprint $table) {
            // Add back the original wallet_type column
            $table->enum('wallet_type', ['trading', 'holding', 'staking'])->nullable()->after('payment_method_id');
        });
    }
};