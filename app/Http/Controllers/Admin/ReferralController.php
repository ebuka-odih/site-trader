<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referral;

class ReferralController extends Controller
{
    public function index()
    {
        $referrals = Referral::with(['referrer', 'referredUser'])
            ->latest()
            ->paginate(25);

        return view('admin.referrals.index', compact('referrals'));
    }
}
