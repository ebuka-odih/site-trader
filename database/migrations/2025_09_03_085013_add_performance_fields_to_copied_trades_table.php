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
        Schema::table('copied_trades', function (Blueprint $table) {
            $table->integer('trade_count')->default(0)->after('amount');
            $table->integer('win')->default(0)->after('trade_count');
            $table->integer('loss')->default(0)->after('win');
            $table->decimal('pnl', 15, 2)->default(0)->after('loss');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('copied_trades', function (Blueprint $table) {
            $table->dropColumn(['trade_count', 'win', 'loss', 'pnl']);
        });
    }
};
