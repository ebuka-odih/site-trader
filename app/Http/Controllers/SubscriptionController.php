<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans = Package::all();
        $user = Auth::user();
        $subscription = Subscription::whereUserId(auth()->id())->latest()->get();
        return view('dashboard.subscription', compact('plans', 'user', 'subscription'));
    }

       public function store(Request $request)
    {
//        return $request;
        $user = Auth::user();
        $package = Package::findOrFail($request->plan_id);

        if ($user->balance <= $package->min_amount) {
            return redirect()->back()->with('error', 'You are not eligible for this plan');
        }

        Subscription::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'package_id' => $package->id,
                'status' => 1,
                'amount' => $request->max_amount
            ]
        );

        $user->update([
            'trader' => 1,
            'package_id' => $request->plan_id,
            'trade_count' => $user->trade_count + $request->trade_limit_per_day,
        ]);

        return redirect()->back()->with('success', 'Subscription Activated Successfully');
    }

    /**
     * Display Trading Plan page
     */
    public function trading()
    {
        $user = Auth::user();
        $tradingPlans = \App\Models\Plan::where('type', 'trading')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('price', 'asc')
            ->get();
        return view('dashboard.plan.trading', compact('user', 'tradingPlans'));
    }

    /**
     * Display Signal Plan page
     */
    public function signal()
    {
        $user = Auth::user();
        $signalPlans = \App\Models\Plan::where('type', 'signal')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('price', 'asc')
            ->get();
        return view('dashboard.plan.signal', compact('user', 'signalPlans'));
    }

    /**
     * Display Mining Plan page
     */
    public function mining()
    {
        $user = Auth::user();
        return view('dashboard.plan.mining', compact('user'));
    }

    /**
     * Display Staking Plan page
     */
    public function staking()
    {
        $user = Auth::user();
        return view('dashboard.plan.staking', compact('user'));
    }
}
