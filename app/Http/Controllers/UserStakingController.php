<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\UserStaking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UserStakingController extends Controller
{
    /**
     * Display user's staking dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's active staking subscriptions
        $activeStakings = UserStaking::where('user_id', $user->id)
            ->active()
            ->with('plan')
            ->latest()
            ->paginate(10);
        
        // Get completed staking subscriptions
        $completedStakings = UserStaking::where('user_id', $user->id)
            ->completed()
            ->with('plan')
            ->latest()
            ->paginate(10);
        
        // Get available staking plans
        $stakingPlans = Plan::ofType('staking')->active()->ordered()->get();
        
        return view('dashboard.staking.index', compact('activeStakings', 'completedStakings', 'stakingPlans'));
    }

    /**
     * Show the form for creating a new staking subscription
     */
    public function create()
    {
        $user = Auth::user();
        $stakingPlans = Plan::ofType('staking')->active()->ordered()->get();
        
        return view('dashboard.staking.create', compact('stakingPlans', 'user'));
    }

    /**
     * Store a newly created staking subscription
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id',
            'amount_staked' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        
        // Check if user account is suspended
        if ($user->isSuspended()) {
            return redirect()->back()
                ->with('error', 'Your account has been suspended. Please contact support for assistance.')
                ->withInput();
        }
        
        $plan = Plan::findOrFail($request->plan_id);

        // Verify it's a staking plan
        if ($plan->type !== 'staking') {
            return redirect()->back()
                ->with('error', 'Selected plan is not a staking plan.')
                ->withInput();
        }

        // Check minimum amount
        if ($plan->minimum_amount && $request->amount_staked < $plan->minimum_amount) {
            return redirect()->back()
                ->with('error', 'Amount must be at least ' . number_format($plan->minimum_amount, 8) . ' ' . $plan->staking_currency)
                ->withInput();
        }

        // Check if user has sufficient balance (you can implement this based on your balance system)
        // For now, we'll assume the user has sufficient balance

        DB::beginTransaction();
        try {
            // Calculate end date based on staking duration
            $endDate = null;
            if ($plan->staking_duration) {
                $endDate = now()->addDays($plan->staking_duration);
            }

            // Create the staking subscription
            $staking = UserStaking::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'amount_staked' => $request->amount_staked,
                'currency' => $plan->staking_currency,
                'apy_rate' => $plan->apy_rate,
                'start_date' => now(),
                'end_date' => $endDate,
                'status' => UserStaking::STATUS_ACTIVE,
                'notes' => $request->notes,
            ]);

            // Here you would typically deduct the amount from user's balance
            // For now, we'll just create the staking record

            DB::commit();

            return redirect()->route('user.staking.show', $staking)
                ->with('success', 'Staking subscription created successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Failed to create staking subscription. Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified staking subscription
     */
    public function show(UserStaking $staking)
    {
        $user = Auth::user();
        
        // Ensure user can only view their own staking
        if ($staking->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        $staking->load('plan');
        
        return view('dashboard.staking.show', compact('staking'));
    }

    /**
     * Cancel the specified staking subscription
     */
    public function cancel(UserStaking $staking)
    {
        $user = Auth::user();
        
        // Ensure user can only cancel their own staking
        if ($staking->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        if (!$staking->isActive()) {
            return redirect()->back()
                ->with('error', 'This staking subscription is not active.');
        }

        // Check if there's a lock period
        if ($staking->plan->lock_period) {
            $lockEndDate = $staking->start_date->addDays($staking->plan->lock_period);
            if (now()->lt($lockEndDate)) {
                return redirect()->back()
                    ->with('error', 'Cannot cancel staking during lock period. Lock period ends on ' . $lockEndDate->format('M d, Y H:i'));
            }
        }

        try {
            $staking->update([
                'status' => UserStaking::STATUS_CANCELLED,
                'end_date' => now(),
            ]);

            // Here you would typically return the staked amount to user's balance
            // For now, we'll just update the status

            return redirect()->route('user.staking.index')
                ->with('success', 'Staking subscription cancelled successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to cancel staking subscription. Error: ' . $e->getMessage());
        }
    }

    /**
     * Withdraw rewards from the specified staking subscription
     */
    public function withdraw(UserStaking $staking)
    {
        $user = Auth::user();
        
        // Ensure user can only withdraw from their own staking
        if ($staking->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        if (!$staking->isActive()) {
            return redirect()->back()
                ->with('error', 'This staking subscription is not active.');
        }

        // Calculate current rewards
        $currentValue = $staking->calculateCurrentValue();
        $rewards = $currentValue - $staking->amount_staked;

        if ($rewards <= 0) {
            return redirect()->back()
                ->with('error', 'No rewards available for withdrawal.');
        }

        try {
            // Update total rewards and last reward date
            $staking->update([
                'total_rewards' => $staking->total_rewards + $rewards,
                'last_reward_date' => now(),
                'current_value' => $currentValue,
            ]);

            // Here you would typically add the rewards to user's balance
            // For now, we'll just update the staking record

            return redirect()->back()
                ->with('success', 'Rewards withdrawn successfully! Amount: ' . number_format($rewards, 8) . ' ' . $staking->currency);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to withdraw rewards. Error: ' . $e->getMessage());
        }
    }

    /**
     * Get staking statistics for user
     */
    public function statistics()
    {
        $user = Auth::user();
        
        // Calculate statistics
        $totalStakings = UserStaking::where('user_id', $user->id)->count();
        $activeStakings = UserStaking::where('user_id', $user->id)->active()->count();
        $totalStaked = UserStaking::where('user_id', $user->id)->active()->sum('amount_staked');
        $totalRewards = UserStaking::where('user_id', $user->id)->sum('total_rewards');
        
        // Get recent stakings
        $recentStakings = UserStaking::where('user_id', $user->id)
            ->with('plan')
            ->latest()
            ->limit(5)
            ->get();
        
        return view('dashboard.staking.statistics', compact(
            'totalStakings',
            'activeStakings',
            'totalStaked',
            'totalRewards',
            'recentStakings'
        ));
    }
}
