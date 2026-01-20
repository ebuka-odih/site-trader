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
        Schema::create('ai_trader_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('ai_trader_plan_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['active', 'paused', 'cancelled', 'expired'])->default('active');
            $table->decimal('monthly_fee', 10, 2);
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->json('payment_details')->nullable(); // Store payment method, transaction IDs, etc.
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['ai_trader_plan_id', 'status']);
            $table->unique(['user_id', 'ai_trader_plan_id', 'status']); // Prevent duplicate active subscriptions
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_trader_subscriptions');
    }
};