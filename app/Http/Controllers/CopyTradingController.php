<?php

namespace App\Http\Controllers;

use App\Models\CopiedTrade;
use App\Models\CopyTrader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CopyTradingController extends Controller
{
    public function index()
    {
        $traders = CopyTrader::all();
        $user = Auth::user();
        $copiedTrades = CopiedTrade::whereUserId(auth()->id())
            ->with('copy_trader')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Get stopped copy trades for this user
        $stoppedCopyTrades = CopiedTrade::whereUserId(auth()->id())
            ->where('status', 0)
            ->whereNotNull('stopped_at')
            ->pluck('copy_trader_id')
            ->toArray();
            
        return view('dashboard.nav.copy-trading', compact('traders', 'user', 'copiedTrades', 'stoppedCopyTrades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'trader_id' => 'required|exists:copy_traders,id',
            'amount' => 'required|numeric|min:1',
        ]);

        try {
            DB::beginTransaction();

            $traderId = $request->trader_id;
            $copyTrader = CopyTrader::findOrFail($traderId);
            $user = Auth::user();

            // Check if user has sufficient trading balance
            if ($copyTrader->amount > $user->trading_balance) {
                if ($request->ajax()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'Insufficient trading balance. You need at least $' . number_format($copyTrader->amount, 2)
                    ]);
                }
                return redirect()->back()->with('error', 'Insufficient trading balance. You need at least $' . number_format($copyTrader->amount, 2));
            }

            // Check if user has a stopped copy trade for this trader
            // If they do, they should use the resume function instead
            $stoppedCopy = CopiedTrade::where('user_id', $user->id)
                ->where('copy_trader_id', $traderId)
                ->where('status', 0)
                ->whereNotNull('stopped_at')
                ->first();

            if ($stoppedCopy) {
                if ($request->ajax()) {
                    return response()->json([
                        'error' => true,
                        'message' => 'You have a stopped copy trade for this trader. Please use the resume option on the trade details page.'
                    ]);
                }
                return redirect()->back()->withInput()->with('error', 'You have a stopped copy trade for this trader. Please use the resume option on the trade details page.');
            }

            // Check if user is already copying this trader
            $existingCopy = CopiedTrade::where('user_id', $user->id)
                ->where('copy_trader_id', $traderId)
                ->where('status', 1)
                ->first();

            if ($existingCopy) {
                if ($request->ajax()) {
                    return response()->json([
                        'warning' => true,
                        'message' => 'You are already copying this trader.'
                    ]);
                }
                return redirect()->back()->withInput()->with('warning', 'You are already copying this trader.');
            }

            // Create the copied trade
            $copiedTrade = new CopiedTrade();
            $copiedTrade->user_id = $user->id;
            $copiedTrade->copy_trader_id = $traderId;
            $copiedTrade->amount = $request->amount;
            $copiedTrade->status = 1; // Active
            $copiedTrade->save();

            // Deduct amount from user trading balance
            $user->trading_balance -= $request->amount;
            $user->save();

            // Create notification for successful copy trade
            $user->createNotification(
                'copy_trade_started',
                'Copy Trade Started',
                'You have successfully started copying ' . $copyTrader->name . ' with $' . number_format($request->amount, 2),
                [
                    'trader_id' => $copyTrader->id,
                    'trader_name' => $copyTrader->name,
                    'amount' => $request->amount,
                    'copied_trade_id' => $copiedTrade->id
                ]
            );

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully started copying ' . $copyTrader->name . ' with $' . number_format($request->amount, 2)
                ]);
            }
            return redirect()->back()->with('success', 'Successfully started copying ' . $copyTrader->name . ' with $' . number_format($request->amount, 2));

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Copy trading error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'error' => true,
                    'message' => 'An error occurred while processing your request. Please try again.'
                ]);
            }
            return redirect()->back()->with('error', 'An error occurred while processing your request. Please try again.');
        }
    }

    public function detail($id)
    {
        $copiedTrade = CopiedTrade::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['copy_trader', 'pnl_histories' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->firstOrFail();

        // Get trader's recent performance data
        $trader = $copiedTrade->copy_trader;
        
        // Use the actual performance metrics from the copied trade (set by admin)
        $tradeCount = $copiedTrade->trade_count ?? 0;
        $wins = $copiedTrade->win ?? 0;
        $losses = $copiedTrade->loss ?? 0;
        $pnl = $copiedTrade->pnl ?? 0;
        
        // Calculate ROI based on actual PnL
        $roi = $copiedTrade->amount > 0 ? ($pnl / $copiedTrade->amount) * 100 : 0;

        $user = Auth::user();

        return view('dashboard.copy-trade-detail', compact(
            'copiedTrade',
            'trader',
            'tradeCount',
            'wins',
            'losses',
            'pnl',
            'roi',
            'user'
        ));
    }

    public function stop($id)
    {
        try {
            DB::beginTransaction();

            $copiedTrade = CopiedTrade::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if ($copiedTrade->status == 0) {
                return redirect()->back()->with('error', 'This trade is already inactive');
            }

            // Stop the copied trade
            $copiedTrade->status = 0; // Inactive
            $copiedTrade->stopped_at = now();
            $copiedTrade->save();

            // Return amount to user trading balance
            $user = Auth::user();
            $user->trading_balance += $copiedTrade->amount;
            $user->save();

            DB::commit();

            return redirect()->back()->with('success', 'Successfully stopped copying ' . $copiedTrade->copy_trader->name);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Stop copy trading error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while stopping the trade. Please try again.');
        }
    }

    public function resume($id)
    {
        try {
            DB::beginTransaction();

            $copiedTrade = CopiedTrade::where('id', $id)
                ->where('user_id', Auth::id())
                ->with('copy_trader')
                ->firstOrFail();

            // Check if trade is actually stopped
            if ($copiedTrade->status == 1) {
                return redirect()->back()->with('error', 'This trade is already active');
            }

            if (!$copiedTrade->stopped_at) {
                return redirect()->back()->with('error', 'This trade cannot be resumed');
            }

            $user = Auth::user();

            // Check if user has sufficient trading balance
            if ($copiedTrade->amount > $user->trading_balance) {
                return redirect()->back()->with('error', 'Insufficient trading balance. You need at least $' . number_format($copiedTrade->amount, 2) . ' to resume this trade.');
            }

            // Check if user is already copying this trader with another active trade
            $existingActiveCopy = CopiedTrade::where('user_id', $user->id)
                ->where('copy_trader_id', $copiedTrade->copy_trader_id)
                ->where('status', 1)
                ->where('id', '!=', $copiedTrade->id)
                ->first();

            if ($existingActiveCopy) {
                return redirect()->back()->with('error', 'You already have an active copy trade for this trader.');
            }

            // Resume the copied trade
            $copiedTrade->status = 1; // Active
            $copiedTrade->stopped_at = null;
            $copiedTrade->save();

            // Deduct amount from user trading balance
            $user->trading_balance -= $copiedTrade->amount;
            $user->save();

            // Create notification for resumed copy trade
            $user->createNotification(
                'copy_trade_resumed',
                'Copy Trade Resumed',
                'You have successfully resumed copying ' . $copiedTrade->copy_trader->name . ' with $' . number_format($copiedTrade->amount, 2),
                [
                    'trader_id' => $copiedTrade->copy_trader->id,
                    'trader_name' => $copiedTrade->copy_trader->name,
                    'amount' => $copiedTrade->amount,
                    'copied_trade_id' => $copiedTrade->id
                ]
            );

            DB::commit();

            return redirect()->back()->with('success', 'Successfully resumed copying ' . $copiedTrade->copy_trader->name);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Resume copy trading error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while resuming the trade. Please try again.');
        }
    }
}
