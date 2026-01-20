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
        Schema::create('trades', function (Blueprint $table) {
//            $table->id();
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('trade_pair_id');
            $table->string('market')->nullable();
            $table->double('amount', 10, 2);
            $table->string('action_type')->nullable(); // buy/sell
            $table->string('status')->default('open');
            $table->integer('leverage')->default(0);
            $table->integer('duration')->default(0);
            $table->integer('stop_loss')->nullable();
            $table->integer('take_profit')->nullable();
            $table->timestamp('executed_at')->nullable();
            $table->double('profit_loss', 10, 2)->default(0); // Profit or loss
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
