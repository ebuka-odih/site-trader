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
        Schema::create('ai_trader_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('number_of_traders');
            $table->json('stocks_trading'); // Array of stock symbols
            $table->decimal('investment_amount', 10, 2)->default(0);
            $table->decimal('risk_level', 3, 2)->default(0.5); // 0.0 to 1.0
            $table->boolean('is_active')->default(true);
            $table->json('features')->nullable(); // Additional features as JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_trader_plans');
    }
};
