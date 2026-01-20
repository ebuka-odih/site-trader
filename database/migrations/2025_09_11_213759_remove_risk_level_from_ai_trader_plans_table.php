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
            $table->dropColumn('risk_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_trader_plans', function (Blueprint $table) {
            $table->decimal('risk_level', 3, 2)->default(0.5);
        });
    }
};
