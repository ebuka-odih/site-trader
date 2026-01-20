<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\TradingSignal;
use App\Models\UserPlan;
use Illuminate\Http\Request;

class UserSignalController extends Controller
{
    /**
     * Display user's signal dashboard
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get user's active signal plan subscriptions
        $userSignalPlans = UserPlan::where('user_id', $user->id)
            ->whereHas('plan', function($query) {
                $query->where('type', 'signal');
            })
            ->where('status', 'active')
            ->with('plan')
            ->get();
        
        // Get active signals from user's subscribed plans
        $activeSignals = TradingSignal::whereIn('plan_id', $userSignalPlans->pluck('plan_id'))
            ->active()
            ->with(['plan'])
            ->latest()
            ->paginate(10);
        
        // Get completed signals
        $completedSignals = TradingSignal::whereIn('plan_id', $userSignalPlans->pluck('plan_id'))
            ->where('status', 'completed')
            ->with(['plan'])
            ->latest()
            ->paginate(10);
        
        return view('dashboard.signals.index', compact('userSignalPlans', 'activeSignals', 'completedSignals'));
    }

    /**
     * Display a specific signal
     */
    public function show(TradingSignal $signal)
    {
        $user = auth()->user();
        
        // Check if user has access to this signal
        $hasAccess = UserPlan::where('user_id', $user->id)
            ->where('plan_id', $signal->plan_id)
            ->where('status', 'active')
            ->exists();
        
        if (!$hasAccess) {
            return redirect()->route('user.signals.index')
                ->with('error', 'You do not have access to this signal.');
        }
        
        $signal->load(['plan', 'creator']);
        
        return view('dashboard.signals.show', compact('signal'));
    }

    /**
     * Copy signal details to clipboard (AJAX)
     */
    public function copySignal(TradingSignal $signal)
    {
        $user = auth()->user();
        
        // Check if user has access to this signal
        $hasAccess = UserPlan::where('user_id', $user->id)
            ->where('plan_id', $signal->plan_id)
            ->where('status', 'active')
            ->exists();
        
        if (!$hasAccess) {
            return response()->json(['error' => 'Access denied'], 403);
        }
        
        // Format signal for copying
        $signalText = $this->formatSignalForCopy($signal);
        
        return response()->json([
            'success' => true,
            'signal_text' => $signalText,
            'message' => 'Signal copied to clipboard!'
        ]);
    }

    /**
     * Get signal statistics for user
     */
    public function statistics()
    {
        $user = auth()->user();
        
        // Get user's signal plan subscriptions
        $userSignalPlans = UserPlan::where('user_id', $user->id)
            ->whereHas('plan', function($query) {
                $query->where('type', 'signal');
            })
            ->where('status', 'active')
            ->with('plan')
            ->get();
        
        $planIds = $userSignalPlans->pluck('plan_id');
        
        // Calculate statistics
        $totalSignals = TradingSignal::whereIn('plan_id', $planIds)->count();
        $activeSignals = TradingSignal::whereIn('plan_id', $planIds)->active()->count();
        $completedSignals = TradingSignal::whereIn('plan_id', $planIds)->where('status', 'completed')->count();
        $profitableSignals = TradingSignal::whereIn('plan_id', $planIds)
            ->where('status', 'completed')
            ->where('profit_loss_percentage', '>', 0)
            ->count();
        
        $successRate = $completedSignals > 0 ? ($profitableSignals / $completedSignals) * 100 : 0;
        
        // Get recent signals
        $recentSignals = TradingSignal::whereIn('plan_id', $planIds)
            ->with(['plan'])
            ->latest()
            ->limit(5)
            ->get();
        
        return view('dashboard.signals.statistics', compact(
            'totalSignals',
            'activeSignals',
            'completedSignals',
            'profitableSignals',
            'successRate',
            'recentSignals',
            'userSignalPlans'
        ));
    }

    /**
     * Format signal for copying to clipboard
     */
    private function formatSignalForCopy(TradingSignal $signal)
    {
        $text = "🚀 {$signal->title}\n\n";
        $text .= "📊 Symbol: {$signal->symbol}\n";
        $text .= "📈 Type: " . ucfirst($signal->type) . "\n";
        $text .= "💰 Entry Price: {$signal->formatted_entry_price}\n";
        
        if ($signal->stop_loss) {
            $text .= "🛑 Stop Loss: {$signal->formatted_stop_loss}\n";
        }
        
        if ($signal->take_profit) {
            $text .= "🎯 Take Profit: {$signal->formatted_take_profit}\n";
        }
        
        if ($signal->risk_reward_ratio) {
            $text .= "⚖️ Risk/Reward: {$signal->formatted_risk_reward_ratio}\n";
        }
        
        if ($signal->timeframe) {
            $text .= "⏰ Timeframe: {$signal->timeframe}\n";
        }
        
        if ($signal->confidence_level) {
            $text .= "🎯 Confidence: {$signal->confidence_level}%\n";
        }
        
        $text .= "⭐ Signal Strength: {$signal->signal_strength_stars}\n";
        
        if ($signal->analysis) {
            $text .= "\n📝 Analysis:\n{$signal->analysis}\n";
        }
        
        if ($signal->tradingview_link) {
            $text .= "\n📊 Chart: {$signal->tradingview_link}\n";
        }
        
        $text .= "\n⏰ Expires: " . ($signal->expires_at ? $signal->expires_at->format('M d, Y H:i') : 'No expiry') . "\n";
        
        return $text;
    }
}
