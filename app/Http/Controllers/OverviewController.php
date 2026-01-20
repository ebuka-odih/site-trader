<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LiveTrade;
use App\Models\BotTrading;
use App\Models\CopiedTrade;
use App\Models\BotTrade;
use App\Models\UserHolding;

class OverviewController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Live Trading Stats
        $liveTrades = LiveTrade::where('user_id', $user->id);
        $openLiveTrades = $liveTrades->where('status', 'pending')->count();
        $closedLiveTrades = $liveTrades->whereIn('status', ['filled', 'cancelled'])->count();
        $totalLiveTrades = $liveTrades->count();
        $liveTradingVolume = $liveTrades->sum('amount');
        
        // Bot Trading Stats
        $botTradings = BotTrading::where('user_id', $user->id);
        $activeBots = $botTradings->where('status', 'active')->count();
        $totalBots = $botTradings->count();
        $totalBotProfit = $botTradings->sum('total_profit');
        $botTradingVolume = $botTradings->sum('total_invested');
        
        // Copy Trading Stats
        $copyTrades = CopiedTrade::where('user_id', $user->id);
        $activeCopyTrades = $copyTrades->where('status', 1)->count();
        $totalCopyTrades = $copyTrades->count();
        $copyTradingVolume = $copyTrades->sum('amount');
        
        // Copy Trading Performance Metrics
        $totalCopyTradeCount = $copyTrades->sum('trade_count');
        $totalCopyWins = $copyTrades->sum('win');
        $totalCopyLosses = $copyTrades->sum('loss');
        $totalCopyPnL = $copyTrades->sum('pnl');
        
        // Portfolio Stats
        $holdings = UserHolding::where('user_id', $user->id)->get();
        $totalHoldingsValue = $holdings->sum('current_value');
        $totalAssets = $holdings->count();
        
        // Overall Stats
        $totalTradingVolume = $liveTradingVolume + $botTradingVolume + $copyTradingVolume;
        $totalProfitLoss = $totalBotProfit + $totalCopyPnL; // Include both bot and copy trade P&L
        
                            return view('dashboard.overview.index', compact(
                        'openLiveTrades',
                        'closedLiveTrades',
                        'totalLiveTrades',
                        'liveTradingVolume',
                        'activeBots',
                        'totalBots',
                        'totalBotProfit',
                        'botTradingVolume',
                        'activeCopyTrades',
                        'totalCopyTrades',
                        'copyTradingVolume',
                        'totalCopyTradeCount',
                        'totalCopyWins',
                        'totalCopyLosses',
                        'totalCopyPnL',
                        'totalHoldingsValue',
                        'totalAssets',
                        'totalTradingVolume',
                        'totalProfitLoss'
                    ));
    }
    
    
}
