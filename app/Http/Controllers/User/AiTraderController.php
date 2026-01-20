<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AiTraderPlan;
use App\Models\AiTrader;

class AiTraderController extends Controller
{
    /**
     * Display the AI Trader plans page for authenticated users
     */
    public function index()
    {
        $plans = AiTraderPlan::where('is_active', true)
            ->withCount('aiTraders')
            ->orderBy('price', 'asc')
            ->get();

        return view('dashboard.ai-traders.index', compact('plans'));
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

        return view('dashboard.ai-traders.plan', compact('plan', 'traders'));
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

        return view('dashboard.ai-traders.trader', compact('trader', 'similarTraders'));
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

    /**
     * Activate an AI Trader for the authenticated user
     */
    public function activateTrader(Request $request, AiTrader $trader)
    {
        try {
            $user = auth()->user();
            
            // Check if user has an active subscription to this plan
            if (!$user->hasActiveAiTraderSubscription($trader->ai_trader_plan_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must subscribe to the ' . $trader->aiTraderPlan->name . ' plan before activating AI Traders.'
                ], 400);
            }
            
            // Check if user already has this trader activated
            $existingActivation = \App\Models\UserAiTrader::where('user_id', $user->id)
                ->where('ai_trader_id', $trader->id)
                ->where('status', 'active')
                ->first();
            
            if ($existingActivation) {
                return response()->json([
                    'success' => false,
                    'message' => 'This AI Trader is already activated for your account.'
                ], 400);
            }
            
            // Get investment amount from request or use minimum
            $investmentAmount = $request->input('investment_amount', $trader->aiTraderPlan->investment_amount);
            $minInvestment = $trader->aiTraderPlan->investment_amount;
            $userBalance = $user->trading_balance;
            
            // Validate investment amount
            if ($investmentAmount < $minInvestment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Investment amount must be at least $' . number_format($minInvestment, 2)
                ], 400);
            }
            
            if ($userBalance < $investmentAmount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient trading balance. Required: $' . number_format($investmentAmount, 2) . ', Available: $' . number_format($userBalance, 2)
                ], 400);
            }
            
            // Create activation record using the model
            $activation = \App\Models\UserAiTrader::create([
                'user_id' => $user->id,
                'ai_trader_id' => $trader->id,
                'ai_trader_plan_id' => $trader->ai_trader_plan_id,
                'investment_amount' => $investmentAmount,
                'status' => 'active',
                'activated_at' => now(),
                'current_balance' => $investmentAmount,
                'total_profit_loss' => 0,
                'total_trades_executed' => 0,
                'winning_trades' => 0,
                'win_rate' => 0
            ]);
            
            // Deduct investment amount from user's trading balance
            $user->decrement('trading_balance', $investmentAmount);
            
            // Create notification for user
            $user->createNotification(
                'ai_trader_activated',
                'AI Trader Activated',
                "Your AI Trader '{$trader->name}' has been successfully activated with $" . number_format($investmentAmount, 2) . " and is now trading with your account.",
                [
                    'ai_trader_id' => $trader->id,
                    'activation_id' => $activation->id,
                    'investment_amount' => $investmentAmount
                ]
            );
            
            return response()->json([
                'success' => true,
                'message' => "AI Trader '{$trader->name}' has been successfully activated with $" . number_format($investmentAmount, 2) . "! It will start trading with your account immediately.",
                'activation_id' => $activation->id
            ]);
            
        } catch (\Exception $e) {
            \Log::error('AI Trader activation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while activating the AI Trader. Please try again.'
            ], 500);
        }
    }

    /**
     * Subscribe to an AI Trader Plan
     */
    public function subscribeToPlan(Request $request, AiTraderPlan $plan)
    {
        try {
            $user = auth()->user();
            
            // Check if user already has an active subscription to this plan
            if ($user->hasActiveAiTraderSubscription($plan->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have an active subscription to the ' . $plan->name . ' plan.'
                ], 400);
            }
            
            // Check if user has sufficient balance for the monthly fee
            $monthlyFee = $plan->price;
            $userBalance = $user->balance; // Use main balance for subscription fees
            
            if ($userBalance < $monthlyFee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient balance. Required: $' . number_format($monthlyFee, 2) . ', Available: $' . number_format($userBalance, 2)
                ], 400);
            }
            
            // Create subscription record
            $subscription = \App\Models\AiTraderSubscription::create([
                'user_id' => $user->id,
                'ai_trader_plan_id' => $plan->id,
                'status' => 'active',
                'monthly_fee' => $monthlyFee,
                'subscribed_at' => now(),
                'expires_at' => now()->addMonth(), // 1 month subscription
                'payment_details' => [
                    'payment_method' => 'balance',
                    'transaction_id' => 'SUB_' . time() . '_' . $user->id,
                    'amount' => $monthlyFee
                ]
            ]);
            
            // Deduct monthly fee from user's balance
            $user->decrement('balance', $monthlyFee);
            
            // Create notification for user
            $user->createNotification(
                'ai_trader_subscription',
                'AI Trader Plan Subscribed',
                "You have successfully subscribed to the '{$plan->name}' AI Trader Plan. You can now activate AI Traders from this plan.",
                [
                    'plan_id' => $plan->id,
                    'subscription_id' => $subscription->id,
                    'monthly_fee' => $monthlyFee,
                    'expires_at' => $subscription->expires_at
                ]
            );
            
            return response()->json([
                'success' => true,
                'message' => "You have successfully subscribed to the '{$plan->name}' plan! You can now activate AI Traders from this plan.",
                'subscription_id' => $subscription->id,
                'expires_at' => $subscription->expires_at->format('M d, Y')
            ]);
            
        } catch (\Exception $e) {
            \Log::error('AI Trader Plan subscription failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while subscribing to the AI Trader Plan. Please try again.'
            ], 500);
        }
    }
}