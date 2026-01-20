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
            if (!Schema::hasColumn('copied_trades', 'stopped_at')) {
                $table->timestamp('stopped_at')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('copied_trades', function (Blueprint $table) {
            if (Schema::hasColumn('copied_trades', 'stopped_at')) {
                $table->dropColumn('stopped_at');
            }
        });
    }
};
