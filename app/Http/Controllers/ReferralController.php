<?php

namespace App\Http\Controllers;

use App\Models\User;

class ReferralController extends Controller
{
    public function handle(string $code)
    {
        $referrer = User::where('referral_code', $code)->first();

        if ($referrer) {
            session(['referrer_code' => $referrer->referral_code]);
            return redirect()->route('register')
                ->with('status', 'You are signing up with ' . $referrer->name . '\'s referral link.');
        }

        return redirect()->route('register')->with('error', 'Referral link is invalid or expired.');
    }
}
