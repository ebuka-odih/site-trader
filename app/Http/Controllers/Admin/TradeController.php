<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trade;
use App\Models\LiveTrade;
use App\Models\TradePair;
use App\Models\User;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    public function index()
    {
        $trades = Trade::all();
        $pairs = TradePair::all();
        $users = User::where('role', 'user')->get();
        return view('admin.trade.trade', compact('trades', 'pairs', 'users'));
    }

    public function store(Request $request)
    {

        $user = $request->get('user_id');
        $amount = $request->input('amount');

        $trade = Trade::create([
            'user_id' => $user,
            'amount' => $amount,
            'status' => 'open',
            'trade_pair_id' => $request->get('trade_pair_id'),
            'leverage' => $request->get('leverage'),
            'duration' => $request->get('duration'),
            'action_type' => $request->get('action_type'),
        ]);

        $user = User::findOrFail($user);
        $user->balance -= $trade->amount;
        $user->save();

        return redirect()->route('admin.openTrades')->with('success', 'Trade placed successfully!');
    }

    public function openTrades(){
        $trades = Trade::latest()->get();
        return view('admin.trade.open-trades', compact('trades'));
    }
    public function closedTrades(){
        $trades = Trade::orderBy('updated_at', 'desc')->get();
        return view('admin.trade.closed-trades', compact('trades'));
    }

    public function tradeHistory(){
        // Get regular trades
        $regularOpenTrades = Trade::where('status', 'open')->latest()->get();
        $regularClosedTrades = Trade::where('status', 'closed')->orderBy('updated_at', 'desc')->get();
        
        // Get live trades (pending and filled are considered "open")
        $liveOpenTrades = LiveTrade::whereIn('status', ['pending', 'filled'])->latest()->get();
        $liveClosedTrades = LiveTrade::where('status', 'closed')->orderBy('updated_at', 'desc')->get();
        
        // Merge both types of trades
        $openTrades = $regularOpenTrades->merge($liveOpenTrades)->sortByDesc('created_at');
        $closedTrades = $regularClosedTrades->merge($liveClosedTrades)->sortByDesc('updated_at');
        
        \Log::info('Trade History Data:', [
            'regular_open_trades' => $regularOpenTrades->count(),
            'live_open_trades' => $liveOpenTrades->count(),
            'regular_closed_trades' => $regularClosedTrades->count(),
            'live_closed_trades' => $liveClosedTrades->count(),
            'total_open' => $openTrades->count(),
            'total_closed' => $closedTrades->count()
        ]);
        
        return view('admin.trade.history', compact('openTrades', 'closedTrades'));
    }



    public function editPnl(Request $request, $id)
    {
        $request->validate([
            'profit_loss' => 'required|numeric',
            'trade_type' => 'sometimes|in:trade,live_trade'
        ]);

        $tradeType = $request->input('trade_type', 'trade');
        
        if ($tradeType === 'live_trade') {
            $trade = LiveTrade::findOrFail($id);
        } else {
            $trade = Trade::findOrFail($id);
        }
        
        $trade->profit_loss = $request->profit_loss;
        $trade->save();

        return response()->json(['success' => true, 'message' => 'PnL updated successfully']);
    }

    public function closeTrade(Request $request, $id)
    {
        $tradeType = $request->input('trade_type', 'trade');
        
        if ($tradeType === 'live_trade') {
            $trade = LiveTrade::findOrFail($id);
        } else {
            $trade = Trade::findOrFail($id);
        }
        
        $profitLoss = $request->get('profit_loss', 0);
        $trade->profit_loss = $profitLoss;
        $trade->status = 'closed';
        $trade->save();
        
        // Update user balance and profit
        $user = User::find($trade->user_id);
        // Return only the original trade amount to balance
        $user->balance += $trade->amount;
        // Add profit/loss only to profit field (not to balance)
        $user->profit = ($user->profit ?? 0) + $profitLoss;
        $user->save();
        
        return redirect()->route('admin.trade.history')->with('success', 'Trade closed successfully!');
    }

    public function destroy(Request $request, $id)
    {
        $tradeType = $request->input('trade_type', 'trade');
        
        if ($tradeType === 'live_trade') {
            $trade = LiveTrade::findOrFail($id);
        } else {
            $trade = Trade::findOrFail($id);
        }
        
        $trade->delete();
        return back()->with('success', 'Trade deleted successfully!');
    }

}
