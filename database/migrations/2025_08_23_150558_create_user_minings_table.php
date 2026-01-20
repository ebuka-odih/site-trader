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
        Schema::create('user_minings', function (Blueprint $table) {
            $table->id();
            
            // User and Plan relationships
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            
            // Investment details
            $table->decimal('amount_invested', 15, 8);
            $table->string('currency', 10)->default('USD');
            
            // Mining specifications
            $table->string('hashrate')->nullable(); // e.g., "1000 TH/s"
            $table->string('equipment')->nullable(); // e.g., "10 Antminer S19"
            $table->string('downtime')->nullable(); // e.g., "99.9% Uptime"
            $table->string('electricity_costs')->nullable(); // e.g., "Included"
            
            // Timeline
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            
            // Status and tracking
            $table->enum('status', ['active', 'completed', 'cancelled', 'suspended'])->default('active');
            $table->decimal('total_mined', 15, 8)->default(0);
            $table->timestamp('last_mining_date')->nullable();
            $table->decimal('current_value', 15, 8)->default(0);
            
            // Additional information
            $table->text('notes')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['plan_id', 'status']);
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_minings');
    }
};
