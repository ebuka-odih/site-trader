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
            // Remove AI-specific fields that are not essential for trading
            $table->dropColumn([
                'ai_model_type',
                'ai_confidence_threshold',
                'ai_learning_parameters',
                'ai_risk_profile',
                'ai_sentiment_analysis',
                'ai_pattern_recognition',
                'ai_market_prediction'
            ]);

            // Add essential trading field
            $table->json('stocks_to_trade')->nullable()->after('trading_strategy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_traders', function (Blueprint $table) {
            // Remove trading field
            $table->dropColumn('stocks_to_trade');

            // Restore AI-specific fields
            $table->string('ai_model_type')->default('GPT-4')->after('trading_strategy');
            $table->decimal('ai_confidence_threshold', 3, 2)->default(0.75)->after('ai_model_type');
            $table->json('ai_learning_parameters')->nullable()->after('ai_confidence_threshold');
            $table->string('ai_risk_profile')->default('balanced')->after('ai_learning_parameters');
            $table->boolean('ai_sentiment_analysis')->default(true)->after('ai_risk_profile');
            $table->boolean('ai_pattern_recognition')->default(true)->after('ai_sentiment_analysis');
            $table->boolean('ai_market_prediction')->default(true)->after('ai_pattern_recognition');
        });
    }
};