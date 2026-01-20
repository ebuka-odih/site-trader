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
        // Clean up leverage field - extract numbers from strings like "Leverage up to 1:500"
        DB::table('plans')->whereNotNull('leverage')->orderBy('id')->each(function ($plan) {
            $leverage = $plan->leverage;
            
            // If it's already a number, keep it
            if (is_numeric($leverage)) {
                return;
            }
            
            // Extract number from strings like "Leverage up to 1:500"
            if (preg_match('/(\d+(?:\.\d+)?)/', $leverage, $matches)) {
                $number = floatval($matches[1]);
                DB::table('plans')->where('id', $plan->id)->update(['leverage' => $number]);
            } else {
                // If no number found, set to null
                DB::table('plans')->where('id', $plan->id)->update(['leverage' => null]);
            }
        });
        
        // Clean up spreads field
        DB::table('plans')->whereNotNull('spreads')->orderBy('id')->each(function ($plan) {
            $spreads = $plan->spreads;
            
            // If it's already a number, keep it
            if (is_numeric($spreads)) {
                return;
            }
            
            // Extract number from strings like "Spreads from 0.8 pips"
            if (preg_match('/(\d+(?:\.\d+)?)/', $spreads, $matches)) {
                $number = floatval($matches[1]);
                DB::table('plans')->where('id', $plan->id)->update(['spreads' => $number]);
            } else {
                // If no number found, set to null
                DB::table('plans')->where('id', $plan->id)->update(['spreads' => null]);
            }
        });
        
        // Clean up swap_fees field
        DB::table('plans')->whereNotNull('swap_fees')->orderBy('id')->each(function ($plan) {
            $swapFees = $plan->swap_fees;
            
            // If it's already a number, keep it
            if (is_numeric($swapFees)) {
                return;
            }
            
            // Check for "No Swap Fees" or similar
            if (stripos($swapFees, 'no') !== false || stripos($swapFees, 'free') !== false) {
                DB::table('plans')->where('id', $plan->id)->update(['swap_fees' => 0]);
            } else {
                // Extract number if present
                if (preg_match('/(\d+(?:\.\d+)?)/', $swapFees, $matches)) {
                    $number = floatval($matches[1]);
                    DB::table('plans')->where('id', $plan->id)->update(['swap_fees' => $number]);
                } else {
                    // If no number found, set to null
                    DB::table('plans')->where('id', $plan->id)->update(['swap_fees' => null]);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cleans up data, so we can't easily reverse it
        // The data would need to be restored from a backup
    }
};
