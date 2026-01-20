<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UserPlanController extends Controller
{
    /**
     * Display a listing of user's plan subscriptions
     */
    public function index()
    {
        $user = Auth::user();
        $userPlans = $user->userPlans()
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.user-plans.index', compact('userPlans', 'user'));
    }

    /**
     * Show the form for creating a new plan subscription
     */
    public function create()
    {
        $user = Auth::user();
        $plans = Plan::where('is_active', true)
            ->orderBy('type', 'asc')
            ->orderBy('price', 'asc')
            ->get()
            ->groupBy('type');

        return view('dashboard.user-plans.create', compact('plans', 'user'));
    }

    /**
     * Store a newly created plan subscription
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id',
            'amount_paid' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        $plan = Plan::findOrFail($request->plan_id);

        // Check if user has sufficient balance based on plan type
        $balanceField = $this->getBalanceFieldForPlanType($plan->type);
        if ($user->$balanceField < $request->amount_paid) {
            return redirect()->back()
                ->with('error', 'Insufficient ' . ucfirst($plan->type) . ' balance to subscribe to this plan.')
                ->withInput();
        }

        // Check if user already has an active plan of the same type
        $existingActivePlan = $user->userPlans()
            ->whereHas('plan', function($query) use ($plan) {
                $query->where('type', $plan->type);
            })
            ->where('status', 'active')
            ->first();

        if ($existingActivePlan) {
            return redirect()->back()
                ->with('error', 'You already have an active ' . ucfirst($plan->type) . ' plan. Please cancel the current plan first.')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Create the user plan subscription
            $userPlan = UserPlan::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'amount_paid' => $request->amount_paid,
                'currency' => $request->currency,
                'start_date' => now(),
                'end_date' => now()->addDays(30), // Default 30 days, can be made configurable
                'notes' => $request->notes,
            ]);

            // Deduct the amount from user's balance
            $user->decrement('balance', $request->amount_paid);

            DB::commit();

            return redirect()->route('user.plans.index')
                ->with('success', 'Plan subscription activated successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Failed to activate plan subscription. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified plan subscription
     */
    public function show(UserPlan $userPlan)
    {
        // Ensure user can only view their own plans
        if ($userPlan->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $userPlan->load('plan', 'user');
        
        // Fetch signals for signal plans
        $signals = collect();
        if ($userPlan->plan->type === 'signal') {
            $signals = \App\Models\TradingSignal::where('plan_id', $userPlan->plan_id)
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view('dashboard.user-plans.show', compact('userPlan', 'signals'));
    }

    /**
     * Show the form for editing the specified plan subscription
     */
    public function edit(UserPlan $userPlan)
    {
        // Ensure user can only edit their own plans
        if ($userPlan->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $plans = Plan::where('is_active', true)->get();
        return view('dashboard.user-plans.edit', compact('userPlan', 'plans'));
    }

    /**
     * Update the specified plan subscription
     */
    public function update(Request $request, UserPlan $userPlan)
    {
        // Ensure user can only update their own plans
        if ($userPlan->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:1000',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $userPlan->update([
            'notes' => $request->notes,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('user.plans.show', $userPlan)
            ->with('success', 'Plan subscription updated successfully!');
    }

    /**
     * Cancel the specified plan subscription
     */
    public function cancel(UserPlan $userPlan)
    {
        // Ensure user can only cancel their own plans
        if ($userPlan->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        if (!$userPlan->isActive()) {
            return redirect()->back()
                ->with('error', 'This plan subscription is not active.');
        }

        $userPlan->cancel();

        return redirect()->route('user.plans.index')
            ->with('success', 'Plan subscription cancelled successfully!');
    }

    /**
     * Reactivate the specified plan subscription
     */
    public function reactivate(UserPlan $userPlan)
    {
        // Ensure user can only reactivate their own plans
        if ($userPlan->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        if ($userPlan->isActive()) {
            return redirect()->back()
                ->with('error', 'This plan subscription is already active.');
        }

        $userPlan->activate();

        return redirect()->route('user.plans.index')
            ->with('success', 'Plan subscription reactivated successfully!');
    }

    /**
     * Remove the specified plan subscription
     */
    public function destroy(UserPlan $userPlan)
    {
        // Ensure user can only delete their own plans
        if ($userPlan->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $userPlan->delete();

        return redirect()->route('user.plans.index')
            ->with('success', 'Plan subscription deleted successfully!');
    }

    /**
     * Subscribe to a plan from the plan listing page
     */
    public function subscribe(Request $request, Plan $plan)
    {
        $validator = Validator::make($request->all(), [
            'amount_paid' => 'required|numeric|min:' . $plan->price,
            'currency' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        // Check if user has sufficient balance based on plan type
        $balanceField = $this->getBalanceFieldForPlanType($plan->type);
        if ($user->$balanceField < $request->amount_paid) {
            return redirect()->back()
                ->with('error', 'Insufficient ' . ucfirst($plan->type) . ' balance to subscribe to this plan.');
        }

        // Check if user already has an active plan of the same type
        $existingActivePlan = $user->userPlans()
            ->whereHas('plan', function($query) use ($plan) {
                $query->where('type', $plan->type);
            })
            ->where('status', 'active')
            ->first();

        if ($existingActivePlan) {
            return redirect()->back()
                ->with('error', 'You already have an active ' . ucfirst($plan->type) . ' plan. Please cancel the current plan first.');
        }

        DB::beginTransaction();
        try {
            // Create the user plan subscription
            $userPlan = UserPlan::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'amount_paid' => $request->amount_paid,
                'currency' => $request->currency,
                'start_date' => now(),
                'end_date' => now()->addDays(30), // Default 30 days
            ]);

            // Deduct the amount from user's balance
            $user->decrement('balance', $request->amount_paid);

            DB::commit();

            return redirect()->route('user.plans.show', $userPlan)
                ->with('success', 'Successfully subscribed to ' . $plan->name . ' plan!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Failed to subscribe to plan. Please try again.');
        }
    }

    /**
     * Get user's current active plans
     */
    public function myPlans()
    {
        $user = Auth::user();
        $activePlans = $user->activeUserPlans()
            ->with('plan')
            ->get()
            ->groupBy('plan.type');

        return view('dashboard.user-plans.my-plans', compact('activePlans', 'user'));
    }

    /**
     * Get plan history
     */
    public function history()
    {
        $user = Auth::user();
        $planHistory = $user->userPlans()
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('dashboard.user-plans.history', compact('planHistory', 'user'));
    }

    /**
     * Get the appropriate balance field for a plan type
     */
    private function getBalanceFieldForPlanType($planType)
    {
        switch ($planType) {
            case 'trading':
                return 'trading_balance';
            case 'mining':
                return 'mining_balance';
            case 'signal':
            case 'staking':
            default:
                return 'balance'; // Use holding balance for other plan types
        }
    }
}
