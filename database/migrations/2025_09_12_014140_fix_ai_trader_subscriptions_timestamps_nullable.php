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
        Schema::table('ai_trader_subscriptions', function (Blueprint $table) {
            // Make timestamp columns nullable to fix MySQL strict mode issues
            $table->timestamp('subscribed_at')->nullable()->change();
            $table->timestamp('expires_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_trader_subscriptions', function (Blueprint $table) {
            // Revert back to non-nullable (this might fail if there are null values)
            $table->timestamp('subscribed_at')->nullable(false)->change();
            $table->timestamp('expires_at')->nullable(false)->change();
        });
    }
};