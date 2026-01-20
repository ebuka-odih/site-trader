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
        Schema::table('copy_traders', function (Blueprint $table) {
            $table->unsignedInteger('copiers_count')
                ->default(0)
                ->after('loss');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('copy_traders', function (Blueprint $table) {
            $table->dropColumn('copiers_count');
        });
    }
};
