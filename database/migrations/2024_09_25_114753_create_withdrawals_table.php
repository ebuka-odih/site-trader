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
        Schema::create('withdrawals', function (Blueprint $table) {
//            $table->id();
            $table->uuid('id')->primary();
            $table->double('amount', 11, 2)->default(0);
            $table->integer('status')->default(0);
            $table->string('payment_method')->nullable();
            $table->string('wallet')->nullable();
            $table->string('address')->nullable();
            $table->string('paypal')->nullable();
            $table->json('bank')->nullable();
            $table->uuid('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
