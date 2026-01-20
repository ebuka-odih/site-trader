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
            // Remove AI Analysis Settings fields
            $table->dropColumn([
                'ai_analysis_depth',
                'ai_decision_speed'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_traders', function (Blueprint $table) {
            // Restore AI Analysis Settings fields
            $table->integer('ai_analysis_depth')->default(5)->after('ai_market_prediction');
            $table->string('ai_decision_speed')->default('medium')->after('ai_analysis_depth');
        });
    }
};