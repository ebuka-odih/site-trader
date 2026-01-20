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
        Schema::create('copied_trade_pnl_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('copied_trade_id');
            $table->decimal('pnl', 15, 2);
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->foreign('copied_trade_id')->references('id')->on('copied_trades')->onDelete('cascade');
            $table->index('copied_trade_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('copied_trade_pnl_histories');
    }
};
