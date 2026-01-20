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
            // Remove generic trading fields
            $table->dropColumn([
                'description',
                'technical_indicators',
                'market_conditions',
                'trading_start_time',
                'trading_end_time',
                'trading_days',
                'news_sentiment_analysis',
                'volume_analysis',
                'price_action_analysis',
                'min_volume_threshold',
                'max_volume_threshold',
                'sector_preferences',
                'market_cap_preferences'
            ]);

            // Add AI + Stock specific fields
            $table->string('ai_model_type')->default('GPT-4')->after('trading_strategy'); // AI model being used
            $table->json('stock_sectors')->nullable()->after('ai_model_type'); // Preferred stock sectors
            $table->json('market_cap_range')->nullable()->after('stock_sectors'); // Market cap preferences
            $table->decimal('ai_confidence_threshold', 3, 2)->default(0.75)->after('market_cap_range'); // AI confidence level
            $table->json('ai_learning_parameters')->nullable()->after('ai_confidence_threshold'); // AI learning settings
            $table->string('ai_risk_profile')->default('balanced')->after('ai_learning_parameters'); // AI risk profile
            $table->json('stock_filters')->nullable()->after('ai_risk_profile'); // Stock filtering criteria
            $table->boolean('ai_sentiment_analysis')->default(true)->after('stock_filters'); // AI sentiment analysis
            $table->boolean('ai_pattern_recognition')->default(true)->after('ai_sentiment_analysis'); // AI pattern recognition
            $table->boolean('ai_market_prediction')->default(true)->after('ai_pattern_recognition'); // AI market prediction
            $table->integer('ai_analysis_depth')->default(5)->after('ai_market_prediction'); // AI analysis depth (days)
            $table->string('ai_decision_speed')->default('medium')->after('ai_analysis_depth'); // AI decision speed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_traders', function (Blueprint $table) {
            // Remove AI + Stock specific fields
            $table->dropColumn([
                'ai_model_type',
                'stock_sectors',
                'market_cap_range',
                'ai_confidence_threshold',
                'ai_learning_parameters',
                'ai_risk_profile',
                'stock_filters',
                'ai_sentiment_analysis',
                'ai_pattern_recognition',
                'ai_market_prediction',
                'ai_analysis_depth',
                'ai_decision_speed'
            ]);

            // Restore generic trading fields
            $table->text('description')->nullable();
            $table->json('technical_indicators')->nullable();
            $table->json('market_conditions')->nullable();
            $table->time('trading_start_time')->nullable();
            $table->time('trading_end_time')->nullable();
            $table->json('trading_days')->nullable();
            $table->boolean('news_sentiment_analysis')->default(false);
            $table->boolean('volume_analysis')->default(false);
            $table->boolean('price_action_analysis')->default(false);
            $table->decimal('min_volume_threshold', 10, 2)->nullable();
            $table->decimal('max_volume_threshold', 10, 2)->nullable();
            $table->json('sector_preferences')->nullable();
            $table->json('market_cap_preferences')->nullable();
        });
    }
};