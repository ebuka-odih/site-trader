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
        Schema::create('user_stakings', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->decimal('amount_staked', 15, 8);
            $table->string('currency', 10); // BTC, ETH, USDT, etc.
            $table->decimal('apy_rate', 5, 2)->nullable();
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->enum('status', ['active', 'completed', 'cancelled', 'expired'])->default('active');
            $table->decimal('total_rewards', 15, 8)->default(0);
            $table->timestamp('last_reward_date')->nullable();
            $table->decimal('current_value', 15, 8)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_stakings');
    }
};
