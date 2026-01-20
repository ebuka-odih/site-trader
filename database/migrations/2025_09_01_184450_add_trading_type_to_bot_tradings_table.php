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
        Schema::table('bot_tradings', function (Blueprint $table) {
            $table->enum('trading_type', ['crypto', 'forex'])->default('crypto')->after('quote_asset');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bot_tradings', function (Blueprint $table) {
            $table->dropColumn('trading_type');
        });
    }
};
