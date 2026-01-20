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
        Schema::table('plans', function (Blueprint $table) {
            // Change leverage, spreads, and swap_fees from string to decimal
            $table->decimal('leverage', 10, 2)->nullable()->change();
            $table->decimal('spreads', 10, 2)->nullable()->change();
            $table->decimal('swap_fees', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            // Revert back to string
            $table->string('leverage')->nullable()->change();
            $table->string('spreads')->nullable()->change();
            $table->string('swap_fees')->nullable()->change();
        });
    }
};
