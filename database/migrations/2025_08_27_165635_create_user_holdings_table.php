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
        Schema::create('user_holdings', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 20, 8)->default(0);
            $table->decimal('average_buy_price', 20, 8)->default(0);
            $table->decimal('total_invested', 20, 8)->default(0);
            $table->decimal('current_value', 20, 8)->default(0);
            $table->decimal('unrealized_pnl', 20, 8)->default(0);
            $table->decimal('unrealized_pnl_percentage', 10, 4)->default(0);
            $table->timestamp('last_updated')->useCurrent();
            $table->timestamps();
            
            $table->unique(['user_id', 'asset_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_holdings');
    }
};
