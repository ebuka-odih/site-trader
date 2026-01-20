<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code')->nullable()->unique();
            $table->foreignUuid('referred_by_id')->nullable()->after('referral_code')->constrained('users');
        });

        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('referred_user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('reward_amount', 15, 2)->default(0);
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        // Backfill referral codes for existing users
        User::select('id')->cursor()->each(function ($user) {
            if (!$user->referral_code) {
                $user->update([
                    'referral_code' => $this->generateUniqueReferralCode(),
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('referred_by_id');
            $table->dropColumn(['referral_code', 'referred_by_id']);
        });
    }

    private function generateUniqueReferralCode(): string
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }
};
