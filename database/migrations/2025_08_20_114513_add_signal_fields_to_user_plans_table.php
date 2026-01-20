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
        Schema::table('user_plans', function (Blueprint $table) {
            $table->integer('signal_quantity_remaining')->nullable()->after('end_date')->comment('Remaining signals for signal plans');
            $table->integer('daily_signals_used')->default(0)->after('signal_quantity_remaining')->comment('Signals used today');
            $table->date('last_signal_date')->nullable()->after('daily_signals_used')->comment('Last date signals were used');
            $table->timestamp('cancelled_at')->nullable()->after('last_signal_date')->comment('When subscription was cancelled');
            $table->timestamp('renewed_at')->nullable()->after('cancelled_at')->comment('When subscription was renewed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_plans', function (Blueprint $table) {
            $table->dropColumn([
                'signal_quantity_remaining',
                'daily_signals_used',
                'last_signal_date',
                'cancelled_at',
                'renewed_at'
            ]);
        });
    }
};
