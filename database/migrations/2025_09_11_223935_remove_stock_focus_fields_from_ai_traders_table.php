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
        Schema::table('ai_traders', function (Blueprint $table) {
            // Remove Stock Focus & Analysis fields
            $table->dropColumn([
                'stock_sectors',
                'market_cap_range',
                'stock_filters'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_traders', function (Blueprint $table) {
            // Restore Stock Focus & Analysis fields
            $table->json('stock_sectors')->nullable()->after('ai_model_type');
            $table->json('market_cap_range')->nullable()->after('stock_sectors');
            $table->json('stock_filters')->nullable()->after('ai_risk_profile');
        });
    }
};