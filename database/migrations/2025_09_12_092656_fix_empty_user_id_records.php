<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Delete empty user_id records in user_ai_traders (these are old records with no valid user)
        $deleted = DB::table('user_ai_traders')
            ->where('user_id', '')
            ->orWhereNull('user_id')
            ->delete();
        
        echo "Deleted {$deleted} empty user_ai_traders records\n";
        
        // Delete empty user_id records in ai_trader_subscriptions (these are old records with no valid user)
        $deleted = DB::table('ai_trader_subscriptions')
            ->where('user_id', '')
            ->orWhereNull('user_id')
            ->delete();
        
        echo "Deleted {$deleted} empty ai_trader_subscriptions records\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not reversible as we don't know which records were originally empty
    }
};