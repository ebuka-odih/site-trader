<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AiTraderPlan;
use App\Models\AiTrader;

class AiTraderController extends Controller
{
    /**
     * Display the AI Trader plans page
     */
    public function index()
    {
        $plans = AiTraderPlan::where('is_active', true)
            ->withCount('aiTraders')
            ->orderBy('price', 'asc')
            ->get();

        return view('ai-traders.index', compact('plans'));
    }

    /**
     * Display a specific AI Trader plan with its traders
     */
    public function showPlan(AiTraderPlan $plan)
    {
        $traders = $plan->aiTraders()
            ->where('is_active', true)
            ->orderBy('current_performance', 'desc')
            ->get();

        return view('ai-traders.plan', compact('plan', 'traders'));
    }

    /**
     * Display a specific AI Trader details
     */
    public function showTrader(AiTrader $trader)
    {
        $trader->load('aiTraderPlan');
        
        // Get similar traders for recommendations
        $similarTraders = AiTrader::where('ai_trader_plan_id', $trader->ai_trader_plan_id)
            ->where('id', '!=', $trader->id)
            ->where('is_active', true)
            ->orderBy('current_performance', 'desc')
            ->limit(3)
            ->get();

        return view('ai-traders.trader', compact('trader', 'similarTraders'));
    }

    /**
     * Get AI Trader performance data for charts
     */
    public function getPerformanceData(AiTrader $trader)
    {
        // Mock performance data - in real implementation, this would come from actual trading data
        $performanceData = [
            'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6', 'Week 7', 'Week 8'],
            'datasets' => [
                [
                    'label' => 'Performance (%)',
                    'data' => [2.1, 4.3, 6.8, 8.2, 11.5, 14.7, 18.3, $trader->current_performance],
                    'borderColor' => $trader->current_performance >= 0 ? '#10B981' : '#EF4444',
                    'backgroundColor' => $trader->current_performance >= 0 ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)',
                    'tension' => 0.4
                ]
            ]
        ];

        return response()->json($performanceData);
    }

    /**
     * Get AI Trader statistics
     */
    public function getTraderStats(AiTrader $trader)
    {
        $stats = [
            'total_trades' => $trader->total_trades,
            'winning_trades' => $trader->winning_trades,
            'win_rate' => $trader->win_rate,
            'current_performance' => $trader->current_performance,
            'avg_trade_duration' => '2.3 days', // Mock data
            'max_drawdown' => '5.2%', // Mock data
            'sharpe_ratio' => '1.8', // Mock data
            'volatility' => '12.4%', // Mock data
        ];

        return response()->json($stats);
    }
}