<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPlan;
use App\Models\UserStaking;
use App\Models\UserMining;

class PortfolioController extends Controller
{
    /**
     * Show the main portfolio page (Plans/Subscriptions tab)
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's all plans
        $allPlans = $user->userPlans()
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate total balance
        $totalBalance = $user->getTotalBalanceAttribute();
        
        // Calculate subscription statistics
        $totalInvestment = $allPlans->sum('amount_paid');
        $activePlans = $allPlans->where('status', 'active')->count();
        $expiredPlans = $allPlans->where('status', 'expired')->count();

        return view('dashboard.portfolio.trade', compact(
            'user',
            'totalBalance'
        ))->with([
            'tradingPlans' => $allPlans,
            'totalTradingInvestment' => $totalInvestment,
            'activeTradingPlans' => $activePlans,
            'expiredTradingPlans' => $expiredPlans
        ]);
    }

    /**
     * Show staking portfolio page
     */
    public function staking()
    {
        $user = Auth::user();
        
        // Get user's staking activities
        $stakings = $user->stakings()->with('plan')->orderBy('created_at', 'desc')->get();
        
        // Calculate total balance
        $totalBalance = $user->getTotalBalanceAttribute();
        
        // Calculate staking statistics
        $totalStaked = $stakings->sum('amount_staked');
        $activeStakings = $stakings->where('status', 'active')->count();
        $totalRewards = $stakings->sum('total_rewards');
        $currentValue = $stakings->sum('current_value');

        return view('dashboard.portfolio.staking', compact(
            'user',
            'stakings',
            'totalBalance',
            'totalStaked',
            'activeStakings',
            'totalRewards',
            'currentValue'
        ));
    }

    /**
     * Show mining portfolio page
     */
    public function mining()
    {
        $user = Auth::user();
        
        // Get user's mining activities
        $minings = $user->minings()->with('plan')->orderBy('created_at', 'desc')->get();
        
        // Calculate total balance
        $totalBalance = $user->getTotalBalanceAttribute();
        
        // Calculate mining statistics
        $totalInvested = $minings->sum('amount_invested');
        $activeMinings = $minings->where('status', 'active')->count();
        $totalMined = $minings->sum('total_mined');
        $currentValue = $minings->sum('current_value');

        return view('dashboard.portfolio.mining', compact(
            'user',
            'minings',
            'totalBalance',
            'totalInvested',
            'activeMinings',
            'totalMined',
            'currentValue'
        ));
    }



    /**
     * Show signal portfolio page
     */
    public function signal()
    {
        $user = Auth::user();
        
        // Get user's signal plans
        $signalPlans = $user->userPlans()
            ->whereHas('plan', function($query) {
                $query->where('type', 'signal');
            })
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate total balance
        $totalBalance = $user->getTotalBalanceAttribute();
        
        // Calculate signal statistics
        $totalSignalInvestment = $signalPlans->sum('amount_paid');
        $activeSignalPlans = $signalPlans->where('status', 'active')->count();
        $totalSignalsRemaining = $signalPlans->where('status', 'active')->sum('signal_quantity_remaining');
        $totalSignalsUsed = $signalPlans->sum('daily_signals_used');

        return view('dashboard.portfolio.signal', compact(
            'user',
            'signalPlans',
            'totalBalance',
            'totalSignalInvestment',
            'activeSignalPlans',
            'totalSignalsRemaining',
            'totalSignalsUsed'
        ));
    }
}
