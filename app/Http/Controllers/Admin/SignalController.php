<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\TradingSignal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SignalController extends Controller
{
    /**
     * Display a listing of trading signals
     */
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'active');
        $planId = $request->get('plan_id');
        
        $query = TradingSignal::with(['plan', 'creator']);
        
        if ($planId) {
            $query->where('plan_id', $planId);
        }
        
        switch ($activeTab) {
            case 'active':
                $signals = $query->active()->latest()->paginate(15);
                break;
            case 'completed':
                $signals = $query->where('status', TradingSignal::STATUS_COMPLETED)->latest()->paginate(15);
                break;
            case 'cancelled':
                $signals = $query->where('status', TradingSignal::STATUS_CANCELLED)->latest()->paginate(15);
                break;
            case 'expired':
                $signals = $query->where('status', TradingSignal::STATUS_EXPIRED)->latest()->paginate(15);
                break;
            default:
                $signals = $query->latest()->paginate(15);
        }
        
        $signalPlans = Plan::ofType('signal')->active()->get();
        $tradePairs = \App\Models\TradePair::all();
        
        return view('admin.signals.index', compact('signals', 'activeTab', 'signalPlans', 'planId', 'tradePairs'));
    }

    /**
     * Show the form for creating a new signal
     */
    public function create(Request $request)
    {
        $planId = $request->get('plan_id');
        $signalPlans = Plan::ofType('signal')->active()->get();
        $selectedPlan = $planId ? Plan::find($planId) : null;
        
        return view('admin.signals.create', compact('signalPlans', 'selectedPlan'));
    }

    /**
     * Store a newly created signal
     */
    public function store(Request $request)
    {
        $validator = $this->validateSignal($request);
        
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $signalData = $this->prepareSignalData($request);
            
            // Handle chart image upload
            if ($request->hasFile('chart_image')) {
                $path = $request->file('chart_image')->store('signals/charts', 'public');
                $signalData['chart_image'] = $path;
            }
            
            $signalData['created_by'] = auth()->id();
            $signal = TradingSignal::create($signalData);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Trading signal created successfully!',
                    'signal' => $signal
                ]);
            }
            
            return redirect()->route('admin.signals.index')
                ->with('success', 'Trading signal created successfully!');
                
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create signal. Error: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Failed to create signal. Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified signal
     */
    public function show(TradingSignal $signal)
    {
        $signal->load(['plan', 'creator']);
        return view('admin.signals.show', compact('signal'));
    }

    /**
     * Show the form for editing the specified signal
     */
    public function edit(TradingSignal $signal)
    {
        $signalPlans = Plan::ofType('signal')->active()->get();
        return view('admin.signals.edit', compact('signal', 'signalPlans'));
    }

    /**
     * Update the specified signal
     */
    public function update(Request $request, TradingSignal $signal)
    {
        $validator = $this->validateSignal($request, $signal->id);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $signalData = $this->prepareSignalData($request);
            
            // Handle chart image upload
            if ($request->hasFile('chart_image')) {
                // Delete old image
                if ($signal->chart_image) {
                    Storage::disk('public')->delete($signal->chart_image);
                }
                
                $path = $request->file('chart_image')->store('signals/charts', 'public');
                $signalData['chart_image'] = $path;
            }
            
            $signal->update($signalData);
            
            return redirect()->route('admin.signals.index')
                ->with('success', 'Trading signal updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update signal. Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified signal
     */
    public function destroy(TradingSignal $signal)
    {
        try {
            // Delete chart image
            if ($signal->chart_image) {
                Storage::disk('public')->delete($signal->chart_image);
            }
            
            $signal->delete();
            
            return redirect()->route('admin.signals.index')
                ->with('success', 'Trading signal deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete signal. Error: ' . $e->getMessage());
        }
    }

    /**
     * Update signal status
     */
    public function updateStatus(Request $request, TradingSignal $signal)
    {
        $request->validate([
            'status' => 'required|in:active,completed,cancelled,expired'
        ]);

        try {
            $status = $request->input('status');
            $signal->status = $status;
            
            // Set timestamps based on status
            switch ($status) {
                case TradingSignal::STATUS_COMPLETED:
                    $signal->completed_at = now();
                    break;
                case TradingSignal::STATUS_CANCELLED:
                    $signal->cancelled_at = now();
                    break;
                case TradingSignal::STATUS_EXPIRED:
                    $signal->expired_at = now();
                    break;
            }
            
            $signal->save();
            
            return redirect()->back()
                ->with('success', "Signal status updated to {$status} successfully!");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update signal status. Error: ' . $e->getMessage());
        }
    }

    /**
     * Validate signal data
     */
    private function validateSignal(Request $request, $signalId = null)
    {
        $rules = [
            'plan_id' => 'required|exists:plans,id',
            'signal_direction' => 'required|in:buy,sell',
            'status' => 'required|in:active,completed,cancelled,expired',
            'entry_price' => 'required|numeric|min:0',
            'target_price' => 'required|numeric|min:0',
            'stop_loss' => 'required|numeric|min:0',
            'expiry_time' => 'required|date|after:now',
        ];

        return Validator::make($request->all(), $rules);
    }

    /**
     * Prepare signal data for storage
     */
    private function prepareSignalData(Request $request)
    {
        $data = $request->only([
            'plan_id', 'status', 'entry_price', 'stop_loss'
        ]);

        // Map form fields to database fields
        $data['title'] = $request->input('trade_pair') . ' ' . ucfirst($request->input('signal_direction')) . ' Signal';
        $data['symbol'] = $request->input('trade_pair');
        $data['type'] = $request->input('signal_direction');
        $data['take_profit'] = $request->input('target_price');
        $data['expires_at'] = $request->input('expiry_time');
        
        // Set is_active to true by default
        $data['is_active'] = true;

        return $data;
    }
}
