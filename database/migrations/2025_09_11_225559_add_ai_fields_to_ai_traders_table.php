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
            // Add AI-related fields
            $table->string('ai_model')->default('GPT-4o')->after('trading_strategy');
            $table->string('ai_confidence')->default('medium')->after('ai_model');
            $table->string('ai_learning_mode')->default('adaptive')->after('ai_confidence');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_traders', function (Blueprint $table) {
            // Remove AI-related fields
            $table->dropColumn([
                'ai_model',
                'ai_confidence',
                'ai_learning_mode'
            ]);
        });
    }
};