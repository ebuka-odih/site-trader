<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    /**
     * Display the plans management page
     */
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'trading');
        
        // Get plans by type
        $tradingPlans = Plan::ofType('trading')->active()->ordered()->get();
        $signalPlans = Plan::ofType('signal')->active()->ordered()->get();
        $stakingPlans = Plan::ofType('staking')->active()->ordered()->get();
        $miningPlans = Plan::ofType('mining')->active()->ordered()->get();
        
        return view('admin.plans.index', compact(
            'activeTab',
            'tradingPlans',
            'signalPlans',
            'stakingPlans',
            'miningPlans'
        ));
    }

    /**
     * Show the form for creating a new plan
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'trading');
        $planTypes = Plan::getTypes();
        
        return view('admin.plans.create', compact('type', 'planTypes'));
    }

    /**
     * Store a newly created plan
     */
    public function store(Request $request)
    {
        // Log the incoming request data for debugging
        \Log::info('Plan creation request:', $request->all());
        
        $validator = $this->validatePlan($request);
        
        if ($validator->fails()) {
            \Log::error('Plan validation failed:', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $planData = $this->preparePlanData($request);
            \Log::info('Prepared plan data:', $planData);
            
            $plan = Plan::create($planData);
            
            \Log::info('Plan created successfully:', ['plan_id' => $plan->id, 'plan_name' => $plan->name]);
            
            return redirect()->route('admin.plans.index', ['tab' => $plan->type])
                ->with('success', 'Plan created successfully!');
                
        } catch (\Exception $e) {
            \Log::error('Plan creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to create plan. Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing a plan
     */
    public function edit(Plan $plan)
    {
        $planTypes = Plan::getTypes();
        
        return view('admin.plans.edit', compact('plan', 'planTypes'));
    }

    /**
     * Update the specified plan
     */
    public function update(Request $request, Plan $plan)
    {
        $validator = $this->validatePlan($request, $plan->id);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $plan->update($this->preparePlanData($request));
            
            return redirect()->route('admin.plans.index', ['tab' => $plan->type])
                ->with('success', 'Plan updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update plan. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified plan
     */
    public function destroy(Plan $plan)
    {
        try {
            $plan->delete();
            
            return redirect()->route('admin.plans.index', ['tab' => $plan->type])
                ->with('success', 'Plan deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete plan. Please try again.');
        }
    }

    /**
     * Toggle plan active status
     */
    public function toggleStatus(Plan $plan)
    {
        try {
            $plan->update(['is_active' => !$plan->is_active]);
            
            $status = $plan->is_active ? 'activated' : 'deactivated';
            
            return redirect()->route('admin.plans.index', ['tab' => $plan->type])
                ->with('success', "Plan {$status} successfully!");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update plan status. Please try again.');
        }
    }

    /**
     * Validate plan data
     */
    private function validatePlan(Request $request, $planId = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|in:trading,signal,staking,mining',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'min_funding' => 'nullable|numeric|min:0',
            'max_funding' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];

        // Add type-specific validation rules
        $type = $request->input('type');
        
        switch ($type) {
            case 'trading':
                $rules = array_merge($rules, [
                    'pairs' => 'nullable|string|max:100',
                    'leverage' => 'nullable|numeric|min:0',
                    'spreads' => 'nullable|numeric|min:0',
                    'swap_fees' => 'nullable|numeric|min:0',
                    'minimum_deposit' => 'nullable|numeric|min:0',
                    'max_lot_size' => 'nullable|string|max:100',
                ]);
                break;
                
            case 'signal':
                $rules = array_merge($rules, [
                    'min_funding' => 'required|numeric|min:0', // Signal amount
                    'signal_strength' => 'required|integer|min:1|max:5',
                    'signal_quantity' => 'required|integer|min:1',
                    'signal_duration' => 'required|integer|min:1',
                    'daily_signals' => 'nullable|integer|min:0',
                    'success_rate' => 'required|numeric|min:0|max:100',
                    'signal_market_type' => 'required|string|in:crypto,forex,stock,commodities,indices',
                    'signal_features' => 'nullable|string', // JSON array
                    'signal_delivery' => 'nullable|string|in:email,telegram,sms,push,webhook',
                    'max_daily_signals' => 'nullable|integer|min:0',
                ]);
                break;
                
            case 'mining':
                $rules = array_merge($rules, [
                    'hashrate' => 'nullable|string|max:100',
                    'equipment' => 'nullable|string|max:255',
                    'downtime' => 'nullable|string|max:100',
                    'electricity_costs' => 'nullable|string|max:100',
                    'mining_duration' => 'nullable|integer|min:0',
                ]);
                break;
                
            case 'staking':
                $rules = array_merge($rules, [
                    'staking_currency' => 'required|string|max:10',
                    'apy_rate' => 'nullable|numeric|min:0',
                    'minimum_amount' => 'nullable|numeric|min:0',
                    'reward_frequency' => 'nullable|string|max:100',
                    'lock_period' => 'nullable|integer|min:0',
                    'staking_duration' => 'nullable|integer|min:0',
                ]);
                break;
        }

        return Validator::make($request->all(), $rules);
    }

    /**
     * Prepare plan data for storage
     */
    private function preparePlanData(Request $request)
    {
        $data = $request->only([
            'name', 'type', 'description', 'currency', 'is_active', 'sort_order'
        ]);

        // Handle pricing based on plan type
        $type = $request->input('type');
        if ($type === 'signal') {
            // For signal plans, use min_funding as the price
            $data['price'] = $request->input('min_funding');
            $data['min_funding'] = $request->input('min_funding');
            $data['max_funding'] = null; // No max funding for signal plans
        } else {
            // For other plans, use regular pricing
            $data['price'] = $request->input('price');
            $data['min_funding'] = $request->input('min_funding');
            $data['max_funding'] = $request->input('max_funding');
        }

        // Set default values
        $data['is_active'] = $request->has('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        
        // Handle max_funding field - convert empty string to null for unlimited funding
        if (isset($data['max_funding']) && ($data['max_funding'] === '' || $data['max_funding'] == 0)) {
            $data['max_funding'] = null;
        }

        // Add type-specific data
        $type = $request->input('type');
        
        switch ($type) {
            case 'trading':
                $data = array_merge($data, $request->only([
                    'pairs', 'leverage', 'spreads', 'swap_fees', 'minimum_deposit', 'max_lot_size'
                ]));
                break;
                
            case 'signal':
                $data = array_merge($data, $request->only([
                    'signal_strength', 'signal_quantity', 'signal_duration', 'daily_signals', 'success_rate',
                    'signal_market_type', 'max_daily_signals'
                ]));
                
                // Set empty signal_pairs array for signal plans (pairs are handled in individual signals)
                $data['signal_pairs'] = [];
                
                if ($request->has('signal_features')) {
                    $signalFeatures = json_decode($request->input('signal_features'), true);
                    $data['signal_features'] = $signalFeatures ?: [];
                }
                break;
                
            case 'mining':
                $data = array_merge($data, $request->only([
                    'hashrate', 'equipment', 'downtime', 'electricity_costs', 'mining_duration'
                ]));
                break;
                
            case 'staking':
                $data = array_merge($data, $request->only([
                    'staking_currency', 'apy_rate', 'minimum_amount', 'reward_frequency', 'lock_period', 'staking_duration'
                ]));
                break;
        }

        // Handle features and terms as JSON
        if ($request->has('features')) {
            $features = array_filter($request->input('features', []));
            $data['features'] = $features;
        }

        if ($request->has('terms_conditions')) {
            $terms = array_filter($request->input('terms_conditions', []));
            $data['terms_conditions'] = $terms;
        }

        return $data;
    }
}
