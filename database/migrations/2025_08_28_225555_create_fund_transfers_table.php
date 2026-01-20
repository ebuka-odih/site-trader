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
        Schema::create('fund_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->string('from_account'); // balance, trading_balance, mining_balance
            $table->string('to_account'); // balance, trading_balance, mining_balance
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['completed', 'pending', 'failed'])->default('completed');
            $table->text('description')->nullable();
            $table->string('reference')->nullable(); // For external reference
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fund_transfers');
    }
};
