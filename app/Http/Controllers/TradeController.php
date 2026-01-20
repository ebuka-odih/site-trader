<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Trade;
use App\Models\TradePair;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TradeController extends Controller
{
    public function index()
    {
        $pairs = TradePair::all();
        if ($pairs->count() > 0) {
            return redirect()->route('user.trade', $pairs->first()->id);
        }
        return redirect()->route('user.dashboard')->with('error', 'No trading pairs available.');
    }

    public function trade($id)
    {
        $pairs = TradePair::all();
        $trade_pair = TradePair::findOrFail($id);
        $user = Auth::user();
        $trades = Trade::whereUserId(auth()->id())->latest()->get();
        $closed_trades = Trade::whereUserId(auth()->id())->orderBy('updated_at', 'desc')->get();
        return view('dashboard.trade.trade', compact('pairs', 'trade_pair', 'user', 'trades', 'closed_trades'));

    }

    public function placeBuyTrade(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'leverage' => 'required',
            'duration' => 'required',
            'stop_loss' => 'nullable',
            'take_profit' => 'nullable',
            'action_type' => 'nullable',
            'trade_pair_id' => 'required',
        ]);

        $user = Auth::user();

        // Check if user account is suspended
        if ($user->isSuspended()) {
            return redirect()->back()->with('error', 'Your account has been suspended. Please contact support for assistance.');
        }

        if (!$user->trader) {
            return redirect()->back()->with('error', 'To initiate a trade, please subscribe to a package first.');
        }

        $tradesToday = Trade::where('user_id', $user->id)->whereDate('created_at', now()->toDateString())->count();

        if ($tradesToday >= $user->trade_count) {
            return redirect()->back()->with('error', 'You have reached your daily trade limit.');
        }

        if ($request->amount > $user->balance) {
            return redirect()->back()->with('error', 'Insufficient Balance');
        }

        $validated['user_id'] = $user->id;
        Trade::create($validated);

        $user->balance -= $request->amount;
        $user->save();

        return redirect()->back()->with('success', 'Buy Order Placed Successfully.');
    }

    public function placeSellTrade(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'leverage' => 'required',
            'duration' => 'required',
            'stop_loss' => 'nullable',
            'take_profit' => 'nullable',
            'action_type' => 'nullable',
            'trade_pair_id' => 'required',
        ]);

        $user = Auth::user();

        // Check if user account is suspended
        if ($user->isSuspended()) {
            return redirect()->back()->with('error', 'Your account has been suspended. Please contact support for assistance.');
        }

        if (!$user->trader) {
            return redirect()->back()->with('error', 'To initiate a trade, please subscribe to a package first.');
        }

        $tradesToday = Trade::where('user_id', $user->id)->whereDate('created_at', now()->toDateString())->count();

        if ($tradesToday >= $user->trade_count) {
            return redirect()->back()->with('error', 'You have reached your daily trade limit.');
        }

        if ($request->amount > $user->balance) {
            return redirect()->back()->with('error', 'Insufficient Balance');
        }

        $validated['user_id'] = $user->id;
        Trade::create($validated);

        $user->balance -= $request->amount;
        $user->save();

        return redirect()->back()->with('success', 'Sell Order Placed Successfully.');
    }


    public function closeTrade($id)
    {
        $trade = Trade::findOrFail($id);
        $trade->status = "closed";
        $trade->save();
        return redirect()->back()->with('success', 'Trade Closed Successfully');
    }


    public function checkTradeDuration()
    {
        $openTrades = Trade::where('status', 'open')->get();
        foreach ($openTrades as $trade) {
            $tradeOpenedAt = Carbon::parse($trade->created_at);
            $tradeDuration = $trade->duration;

            if (now()->diffInMinutes($tradeOpenedAt) >= $tradeDuration) {
                $trade->status = 'closed';
                $trade->save();
            }
        }
//        dd($openTrades);
    }


     public function dummyTrade(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'leverage' => 'required',
            'duration' => 'required',
            'stop_loss' => 'nullable',
            'take_profit' => 'nullable',
            'action_type' => 'nullable',
            'trade_pair_id' => 'required',
        ]);

        $user = Auth::user();

        if (!$user->trader) {
            return redirect()->back()->with('error', 'To initiate a trade, please subscribe to a package first.');
        }

        $subscription = Subscription::whereUserId($user->id)->latest()->first();

         if (!$subscription) {
            return redirect()->back()->with('error', 'You do not have an active subscription. Please subscribe to continue trading.');
        }

        $endingDate = Carbon::parse($subscription->ending_date);

        if (now()->greaterThanOrEqualTo($endingDate)) {
            return redirect()->back()->with('error', 'Your package duration has ended. Please renew your subscription to continue trading.');
        }

        $tradesToday = Trade::where('user_id', $user->id)->whereDate('created_at', now()->toDateString())->count();

        if ($tradesToday >= $user->trade_count) {
            return redirect()->back()->with('error', 'You have reached your daily trade limit.');
        }

        if ($request->amount > $user->balance) {
            return redirect()->back()->with('error', 'Insufficient Balance');
        }

        $validated['user_id'] = $user->id;
        Trade::create($validated);

        $user->balance -= $request->amount;
        $user->save();

        return redirect()->back()->with('success', 'Buy Order Placed Successfully.');
    }

}
