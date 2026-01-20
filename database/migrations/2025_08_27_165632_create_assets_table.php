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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('symbol', 20)->unique();
            $table->string('name', 100);
            $table->enum('type', ['crypto', 'stock']);
            $table->decimal('current_price', 20, 8)->default(0);
            $table->decimal('market_cap', 20, 2)->default(0);
            $table->decimal('volume_24h', 20, 2)->default(0);
            $table->decimal('price_change_24h', 10, 4)->default(0);
            $table->decimal('price_change_percentage_24h', 10, 4)->default(0);
            $table->timestamp('last_updated')->useCurrent();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
