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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            
            // Basic plan information
            $table->string('name');
            $table->enum('type', ['trading', 'signal', 'staking', 'mining']);
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->decimal('original_price', 15, 2)->nullable();
            $table->string('currency', 10)->default('$');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            
            // Trading plan specific fields
            $table->string('pairs')->nullable();
            $table->string('leverage')->nullable();
            $table->string('spreads')->nullable();
            $table->string('swap_fees')->nullable();
            $table->decimal('minimum_deposit', 15, 2)->nullable();
            $table->string('max_lot_size')->nullable();
            
            // Signal plan specific fields
            $table->integer('signal_strength')->nullable();
            $table->integer('daily_signals')->nullable();
            $table->decimal('success_rate', 5, 2)->nullable();
            $table->integer('signal_duration')->nullable();
            
            // Mining plan specific fields
            $table->string('hashrate')->nullable();
            $table->string('equipment')->nullable();
            $table->string('downtime')->nullable();
            $table->string('electricity_costs')->nullable();
            $table->integer('mining_duration')->nullable();
            
            // Staking plan specific fields
            $table->decimal('apy_rate', 5, 2)->nullable();
            $table->decimal('minimum_amount', 15, 2)->nullable();
            $table->string('reward_frequency')->nullable();
            $table->integer('lock_period')->nullable();
            $table->integer('staking_duration')->nullable();
            
            // Additional fields
            $table->json('features')->nullable();
            $table->json('terms_conditions')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('type');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
