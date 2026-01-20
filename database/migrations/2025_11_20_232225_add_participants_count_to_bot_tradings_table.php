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
            $table->unsignedInteger('participants_count')
                ->default(0)
                ->after('success_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bot_tradings', function (Blueprint $table) {
            $table->dropColumn('participants_count');
        });
    }
};
