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
        Schema::table('trade_pairs', function (Blueprint $table) {
            $table->decimal('current_price', 18, 8)->nullable()->after('pair');
            $table->decimal('price_change_24h', 10, 4)->nullable()->after('current_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trade_pairs', function (Blueprint $table) {
            $table->dropColumn(['current_price', 'price_change_24h']);
        });
    }
};
