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
        Schema::create('user_ai_traders', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('ai_trader_id')->constrained()->onDelete('cascade');
            $table->foreignId('ai_trader_plan_id')->constrained()->onDelete('cascade');
            $table->decimal('investment_amount', 15, 2);
            $table->enum('status', ['active', 'paused', 'stopped', 'completed'])->default('active');
            $table->timestamp('activated_at');
            $table->timestamp('paused_at')->nullable();
            $table->timestamp('stopped_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->decimal('current_balance', 15, 2)->nullable();
            $table->decimal('total_profit_loss', 15, 2)->default(0);
            $table->integer('total_trades_executed')->default(0);
            $table->integer('winning_trades')->default(0);
            $table->decimal('win_rate', 5, 2)->default(0);
            $table->json('settings')->nullable(); // Store any custom settings
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['ai_trader_id', 'status']);
            $table->unique(['user_id', 'ai_trader_id', 'status']); // Prevent duplicate active activations
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_ai_traders');
    }
};