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
        Schema::table('live_trades', function (Blueprint $table) {
            $table->decimal('entry_price', 20, 8)->nullable()->after('amount');
            $table->decimal('exit_price', 20, 8)->nullable()->after('entry_price');
            $table->decimal('profit_loss', 20, 2)->nullable()->after('exit_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('live_trades', function (Blueprint $table) {
            $table->dropColumn(['entry_price', 'exit_price', 'profit_loss']);
        });
    }
};
