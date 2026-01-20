<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SignalSubscriptionController extends Controller
{
    /**
     * Display a listing of user's signal subscriptions
     */
    public function index()
    {
        $user = Auth::user();
        $signalSubscriptions = UserPlan::where('user_id', $user->id)
            ->whereHas('plan', function($query) {
                $query->where('type', 'signal');
            })
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.signal-subscriptions.index', compact('user', 'signalSubscriptions'));
    }

    /**
     * Show the form for creating a new signal subscription
     */
    public function create()
    {
        $user = Auth::user();
        $signalPlans = Plan::where('type', 'signal')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('price', 'asc')
            ->get();

        return view('dashboard.signal-subscriptions.create', compact('user', 'signalPlans'));
    }

    /**
     * Store a newly created signal subscription
     */
    public function store(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'amount' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $plan = Plan::findOrFail($request->plan_id);

        // Verify it's a signal plan
        if ($plan->type !== 'signal') {
            return redirect()->back()->with('error', 'Invalid plan type selected.');
        }

        // Check if user has sufficient balance
        if ($user->balance < $request->amount) {
            return redirect()->back()->with('error', 'Insufficient balance. Please fund your account first.');
        }

        // Check if user already has an active subscription for this plan
        $existingSubscription = UserPlan::where('user_id', $user->id)
            ->where('plan_id', $plan->id)
            ->where('status', 'active')
            ->first();

        if ($existingSubscription) {
            return redirect()->back()->with('error', 'You already have an active subscription for this plan.');
        }

        try {
            DB::beginTransaction();

            // Create the subscription
            $subscription = UserPlan::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'amount_paid' => $request->amount,
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addDays($plan->signal_duration),
                'signal_quantity_remaining' => $plan->signal_quantity,
                'daily_signals_used' => 0,
                'last_signal_date' => null,
            ]);

            // Deduct amount from user balance
            $user->decrement('balance', $request->amount);

            // Log the transaction
            Log::info('Signal subscription created', [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'amount' => $request->amount,
                'subscription_id' => $subscription->id
            ]);

            DB::commit();

            return redirect()->route('user.signal-subscriptions.show', $subscription)
                ->with('success', 'Signal subscription activated successfully! You can now access your signals.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Signal subscription creation failed', [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Failed to create subscription. Please try again.');
        }
    }

    /**
     * Display the specified signal subscription
     */
    public function show(UserPlan $subscription)
    {
        $user = Auth::user();

        // Ensure user can only view their own subscriptions
        if ($subscription->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure it's a signal plan
        if ($subscription->plan->type !== 'signal') {
            abort(404, 'Subscription not found.');
        }

        $subscription->load('plan');

        return view('dashboard.signal-subscriptions.show', compact('user', 'subscription'));
    }

    /**
     * Show the form for editing the specified signal subscription
     */
    public function edit(UserPlan $subscription)
    {
        $user = Auth::user();

        // Ensure user can only edit their own subscriptions
        if ($subscription->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure it's a signal plan
        if ($subscription->plan->type !== 'signal') {
            abort(404, 'Subscription not found.');
        }

        $subscription->load('plan');

        return view('dashboard.signal-subscriptions.edit', compact('user', 'subscription'));
    }

    /**
     * Update the specified signal subscription
     */
    public function update(Request $request, UserPlan $subscription)
    {
        $user = Auth::user();

        // Ensure user can only update their own subscriptions
        if ($subscription->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure it's a signal plan
        if ($subscription->plan->type !== 'signal') {
            abort(404, 'Subscription not found.');
        }

        $request->validate([
            'status' => 'sometimes|in:active,paused,cancelled',
        ]);

        try {
            $subscription->update($request->only(['status']));

            return redirect()->route('user.signal-subscriptions.show', $subscription)
                ->with('success', 'Subscription updated successfully.');

        } catch (\Exception $e) {
            Log::error('Signal subscription update failed', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Failed to update subscription. Please try again.');
        }
    }

    /**
     * Cancel the specified signal subscription
     */
    public function cancel(UserPlan $subscription)
    {
        $user = Auth::user();

        // Ensure user can only cancel their own subscriptions
        if ($subscription->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure it's a signal plan
        if ($subscription->plan->type !== 'signal') {
            abort(404, 'Subscription not found.');
        }

        try {
            $subscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            return redirect()->route('user.signal-subscriptions.index')
                ->with('success', 'Subscription cancelled successfully.');

        } catch (\Exception $e) {
            Log::error('Signal subscription cancellation failed', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Failed to cancel subscription. Please try again.');
        }
    }

    /**
     * Renew the specified signal subscription
     */
    public function renew(UserPlan $subscription)
    {
        $user = Auth::user();

        // Ensure user can only renew their own subscriptions
        if ($subscription->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure it's a signal plan
        if ($subscription->plan->type !== 'signal') {
            abort(404, 'Subscription not found.');
        }

        $plan = $subscription->plan;

        // Check if user has sufficient balance
        if ($user->balance < $plan->price) {
            return redirect()->back()->with('error', 'Insufficient balance. Please fund your account first.');
        }

        try {
            DB::beginTransaction();

            // Update subscription
            $subscription->update([
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addDays($plan->signal_duration),
                'signal_quantity_remaining' => $plan->signal_quantity,
                'daily_signals_used' => 0,
                'last_signal_date' => null,
                'renewed_at' => now(),
            ]);

            // Deduct amount from user balance
            $user->decrement('balance', $plan->price);

            DB::commit();

            return redirect()->route('user.signal-subscriptions.show', $subscription)
                ->with('success', 'Subscription renewed successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Signal subscription renewal failed', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Failed to renew subscription. Please try again.');
        }
    }

    /**
     * Display user's active signal subscriptions
     */
    public function active()
    {
        $user = Auth::user();
        $activeSubscriptions = UserPlan::where('user_id', $user->id)
            ->where('status', 'active')
            ->whereHas('plan', function($query) {
                $query->where('type', 'signal');
            })
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.signal-subscriptions.active', compact('user', 'activeSubscriptions'));
    }

    /**
     * Display user's signal subscription history
     */
    public function history()
    {
        $user = Auth::user();
        $subscriptionHistory = UserPlan::where('user_id', $user->id)
            ->whereHas('plan', function($query) {
                $query->where('type', 'signal');
            })
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.signal-subscriptions.history', compact('user', 'subscriptionHistory'));
    }
}
