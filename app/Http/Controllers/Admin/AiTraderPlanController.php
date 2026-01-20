<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiTraderPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AiTraderPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = AiTraderPlan::with('aiTraders')->paginate(10);
        return view('admin.ai-trader-plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ai-trader-plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'number_of_traders' => 'required|integer|min:1',
            'stocks_trading' => 'required|array|min:1',
            'stocks_trading.*' => 'required|string|max:10',
            'investment_amount' => 'required|numeric|min:0',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $plan = AiTraderPlan::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'AI Trader Plan created successfully',
            'plan' => $plan
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(AiTraderPlan $aiTraderPlan)
    {
        $aiTraderPlan->load('aiTraders');
        return response()->json([
            'success' => true,
            'plan' => $aiTraderPlan
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AiTraderPlan $aiTraderPlan)
    {
        return view('admin.ai-trader-plans.edit', compact('aiTraderPlan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AiTraderPlan $aiTraderPlan)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'number_of_traders' => 'required|integer|min:1',
            'stocks_trading' => 'required|array|min:1',
            'stocks_trading.*' => 'required|string|max:10',
            'investment_amount' => 'required|numeric|min:0',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $aiTraderPlan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'AI Trader Plan updated successfully',
            'plan' => $aiTraderPlan
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AiTraderPlan $aiTraderPlan)
    {
        if ($aiTraderPlan->aiTraders()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete plan with active AI traders'
            ], 422);
        }

        $aiTraderPlan->delete();

        return response()->json([
            'success' => true,
            'message' => 'AI Trader Plan deleted successfully'
        ]);
    }

    /**
     * Toggle plan status
     */
    public function toggleStatus(AiTraderPlan $aiTraderPlan)
    {
        $aiTraderPlan->update(['is_active' => !$aiTraderPlan->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Plan status updated successfully',
            'is_active' => $aiTraderPlan->is_active
        ]);
    }
}
