<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiTrader;
use App\Models\AiTraderPlan;
use App\Models\UserAiTrader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AiTraderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $traders = AiTrader::with('aiTraderPlan')->paginate(10);
        return view('admin.ai-traders.index', compact('traders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $plans = AiTraderPlan::where('is_active', true)->get();
        return view('admin.ai-traders.create', compact('plans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ai_trader_plan_id' => 'required|exists:ai_trader_plans,id',
            'name' => 'required|string|max:255',
            'trading_strategy' => 'required|in:conservative,moderate,aggressive,scalping,swing,day_trading',
            'ai_model' => 'required|in:GPT-4o-Trader,GPT-4-Trader,GPT-4-Turbo-Trader,Claude-3.5-Sonnet-Trader,Claude-3-Opus-Trader,Gemini-Pro-Trader,Gemini-Ultra-Trader,Llama-3.1-405B-Trader,Mixtral-8x22B-Trader,Alpha-Trader-X1,Quantum-Trader-Pro,Neural-Trader-Elite,Cyber-Trader-Max,Phoenix-Trader-AI',
            'ai_confidence' => 'required|in:low,medium,high,maximum',
            'ai_learning_mode' => 'required|in:conservative,adaptive,aggressive,experimental',
            'stocks_to_trade' => 'required|array|min:1',
            'stocks_to_trade.*' => 'required|string|max:10',
            'risk_tolerance' => 'required|numeric|min:0|max:1',
            'stop_loss_percentage' => 'required|numeric|min:0|max:100',
            'take_profit_percentage' => 'required|numeric|min:0|max:100',
            'max_positions' => 'required|integer|min:1|max:50',
            'position_size_percentage' => 'required|numeric|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $trader = AiTrader::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'AI Trader created successfully',
            'trader' => $trader->load('aiTraderPlan')
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(AiTrader $aiTrader)
    {
        $aiTrader->load('aiTraderPlan');
        return response()->json([
            'success' => true,
            'trader' => $aiTrader
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AiTrader $aiTrader)
    {
        $plans = AiTraderPlan::where('is_active', true)->get();
        return view('admin.ai-traders.edit', compact('aiTrader', 'plans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AiTrader $aiTrader)
    {
        $validator = Validator::make($request->all(), [
            'ai_trader_plan_id' => 'required|exists:ai_trader_plans,id',
            'name' => 'required|string|max:255',
            'trading_strategy' => 'required|in:conservative,moderate,aggressive,scalping,swing,day_trading',
            'ai_model' => 'required|in:GPT-4o-Trader,GPT-4-Trader,GPT-4-Turbo-Trader,Claude-3.5-Sonnet-Trader,Claude-3-Opus-Trader,Gemini-Pro-Trader,Gemini-Ultra-Trader,Llama-3.1-405B-Trader,Mixtral-8x22B-Trader,Alpha-Trader-X1,Quantum-Trader-Pro,Neural-Trader-Elite,Cyber-Trader-Max,Phoenix-Trader-AI',
            'ai_confidence' => 'required|in:low,medium,high,maximum',
            'ai_learning_mode' => 'required|in:conservative,adaptive,aggressive,experimental',
            'stocks_to_trade' => 'required|array|min:1',
            'stocks_to_trade.*' => 'required|string|max:10',
            'risk_tolerance' => 'required|numeric|min:0|max:1',
            'stop_loss_percentage' => 'required|numeric|min:0|max:100',
            'take_profit_percentage' => 'required|numeric|min:0|max:100',
            'max_positions' => 'required|integer|min:1|max:50',
            'position_size_percentage' => 'required|numeric|min:1|max:100',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $aiTrader->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'AI Trader updated successfully',
            'trader' => $aiTrader->load('aiTraderPlan')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AiTrader $aiTrader)
    {
        $aiTrader->delete();

        return response()->json([
            'success' => true,
            'message' => 'AI Trader deleted successfully'
        ]);
    }

    /**
     * Toggle trader status
     */
    public function toggleStatus(AiTrader $aiTrader)
    {
        $aiTrader->update(['is_active' => !$aiTrader->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Trader status updated successfully',
            'is_active' => $aiTrader->is_active
        ]);
    }

    /**
     * Get traders by plan
     */
    public function getByPlan(AiTraderPlan $aiTraderPlan)
    {
        $traders = $aiTraderPlan->aiTraders()->get();
        
        return response()->json([
            'success' => true,
            'traders' => $traders
        ]);
    }

    /**
     * Show trader management dashboard
     */
    public function management()
    {
        $activeTraders = UserAiTrader::with(['user', 'aiTrader', 'aiTraderPlan'])
            ->where('status', 'active')
            ->orderBy('activated_at', 'desc')
            ->paginate(15);

        $totalActiveTraders = UserAiTrader::where('status', 'active')->count();
        $totalInvestment = UserAiTrader::where('status', 'active')->sum('investment_amount');
        $totalProfitLoss = UserAiTrader::where('status', 'active')->sum('total_profit_loss');

        return view('admin.ai-traders.management', compact(
            'activeTraders', 
            'totalActiveTraders', 
            'totalInvestment', 
            'totalProfitLoss'
        ));
    }

    /**
     * Show trader history and details
     */
    public function traderHistory(UserAiTrader $userAiTrader)
    {
        $userAiTrader->load(['user', 'aiTrader', 'aiTraderPlan']);
        
        // Get recent trades (we'll create a trades table later)
        $recentTrades = collect(); // Placeholder for now
        
        return view('admin.ai-traders.history', compact('userAiTrader', 'recentTrades'));
    }

    /**
     * Update trader performance
     */
    public function updatePerformance(Request $request, UserAiTrader $userAiTrader)
    {
        $validator = Validator::make($request->all(), [
            'current_balance' => 'required|numeric|min:0',
            'total_profit_loss' => 'required|numeric',
            'total_trades_executed' => 'required|integer|min:0',
            'winning_trades' => 'required|integer|min:0',
            'win_rate' => 'required|numeric|min:0|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $userAiTrader->update($request->only([
            'current_balance',
            'total_profit_loss',
            'total_trades_executed',
            'winning_trades',
            'win_rate'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Trader performance updated successfully',
            'trader' => $userAiTrader->fresh()
        ]);
    }

    /**
     * Create a new trade for a trader
     */
    public function createTrade(Request $request, UserAiTrader $userAiTrader)
    {
        $validator = Validator::make($request->all(), [
            'stock_symbol' => 'required|string|max:10',
            'trade_type' => 'required|in:buy,sell',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0.01',
            'profit_loss' => 'required|numeric',
            'trade_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // For now, we'll just update the trader's stats
        // Later we can create a separate trades table
        $tradeAmount = $request->quantity * $request->price;
        $profitLoss = $request->profit_loss;

        // Update trader statistics
        $userAiTrader->increment('total_trades_executed');
        $userAiTrader->increment('current_balance', $profitLoss);
        $userAiTrader->increment('total_profit_loss', $profitLoss);

        if ($profitLoss > 0) {
            $userAiTrader->increment('winning_trades');
        }

        // Recalculate win rate
        $winRate = $userAiTrader->total_trades_executed > 0 
            ? ($userAiTrader->winning_trades / $userAiTrader->total_trades_executed) * 100 
            : 0;
        
        $userAiTrader->update(['win_rate' => $winRate]);

        return response()->json([
            'success' => true,
            'message' => 'Trade created successfully',
            'trader' => $userAiTrader->fresh()
        ]);
    }

    /**
     * Get trader performance data for charts
     */
    public function getPerformanceData(UserAiTrader $userAiTrader)
    {
        // Generate sample performance data for the last 30 days
        $performanceData = [];
        $currentBalance = $userAiTrader->current_balance;
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $randomChange = rand(-50, 100) / 100; // Random change between -0.5% and 1%
            $currentBalance += $currentBalance * $randomChange;
            
            $performanceData[] = [
                'date' => $date,
                'balance' => round($currentBalance, 2),
                'profit_loss' => round($currentBalance - $userAiTrader->investment_amount, 2)
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $performanceData
        ]);
    }
}
