<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserMining;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MiningController extends Controller
{
    /**
     * Display a listing of all user mining activities.
     */
    public function index()
    {
        $miningActivities = UserMining::with(['user', 'plan'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_mining' => UserMining::count(),
            'active_mining' => UserMining::where('status', 'active')->count(),
            'completed_mining' => UserMining::where('status', 'completed')->count(),
            'total_invested' => UserMining::sum('amount_invested'),
            'total_mined' => UserMining::sum('total_mined'),
            'total_value' => UserMining::sum('current_value'),
        ];

        return view('admin.mining.index', compact('miningActivities', 'stats'));
    }


    /**
     * Update mining stats and PnL.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'total_mined' => 'required|numeric|min:0',
            'current_value' => 'required|numeric|min:0',
            'status' => 'required|in:active,completed,cancelled,suspended',
            'notes' => 'nullable|string|max:1000',
        ]);

        $mining = UserMining::findOrFail($id);
        $oldValue = $mining->current_value;
        
        $mining->update([
            'total_mined' => $request->total_mined,
            'current_value' => $request->current_value,
            'status' => $request->status,
            'notes' => $request->notes,
            'last_mining_date' => now(),
        ]);

        // Calculate PnL difference
        $pnlDifference = $request->current_value - $oldValue;
        
        // If there's a positive PnL difference, add it to user's profit field (not balance)
        // This ensures profit is tracked separately and doesn't go to balance without trace
        if ($pnlDifference > 0) {
            $user = $mining->user;
            $user->profit = ($user->profit ?? 0) + $pnlDifference;
            $user->save();
            
            \Log::info("Mining PnL updated for user {$user->id}. PnL difference: \${$pnlDifference}. New profit: \${$user->profit}");
        }

        return redirect()->route('admin.mining.index')
            ->with('success', 'Mining stats updated successfully! ' . 
                ($pnlDifference != 0 ? "User balance adjusted by $" . number_format($pnlDifference, 2) : ""));
    }

    /**
     * Suspend a mining activity.
     */
    public function suspend($id)
    {
        $mining = UserMining::findOrFail($id);
        $mining->update(['status' => 'suspended']);

        // Send notification to user
        $mining->user->createNotification(
            'mining_suspended',
            'Mining Suspended',
            "Your mining activity with {$mining->plan->name} has been suspended by admin.",
            [
                'mining_id' => $mining->id,
                'plan_name' => $mining->plan->name,
                'amount_invested' => $mining->amount_invested
            ]
        );

        return redirect()->back()->with('success', 'Mining activity suspended successfully!');
    }

    /**
     * Resume a mining activity.
     */
    public function resume($id)
    {
        $mining = UserMining::findOrFail($id);
        $mining->update(['status' => 'active']);

        // Send notification to user
        $mining->user->createNotification(
            'mining_resumed',
            'Mining Resumed',
            "Your mining activity with {$mining->plan->name} has been resumed by admin.",
            [
                'mining_id' => $mining->id,
                'plan_name' => $mining->plan->name,
                'amount_invested' => $mining->amount_invested
            ]
        );

        return redirect()->back()->with('success', 'Mining activity resumed successfully!');
    }

    /**
     * Cancel a mining activity.
     */
    public function cancel($id)
    {
        $mining = UserMining::findOrFail($id);
        
        // If mining was active and has current value, separate investment return from profit
        if ($mining->status === 'active' && $mining->current_value > 0) {
            $user = $mining->user;
            
            // Return original investment to balance
            $user->balance += $mining->amount_invested;
            
            // Add profit (if any) to profit field, not balance
            $profit = $mining->current_value - $mining->amount_invested;
            if ($profit > 0) {
                $user->profit = ($user->profit ?? 0) + $profit;
            }
            
            $user->save();
            
            \Log::info("Mining cancelled for user {$user->id}. Investment of \${$mining->amount_invested} returned to balance, profit of \${$profit} added to profit field. New balance: \${$user->balance}, New profit: \${$user->profit}");
        }

        $mining->update([
            'status' => 'cancelled',
            'end_date' => now(),
            'current_value' => 0
        ]);

        // Send notification to user
        $mining->user->createNotification(
            'mining_cancelled',
            'Mining Cancelled',
            "Your mining activity with {$mining->plan->name} has been cancelled by admin." . 
            ($mining->current_value > 0 ? " Value of $" . number_format($mining->current_value, 2) . " has been transferred to your balance." : ""),
            [
                'mining_id' => $mining->id,
                'plan_name' => $mining->plan->name,
                'amount_invested' => $mining->amount_invested,
                'value_transferred' => $mining->current_value
            ]
        );

        return redirect()->back()->with('success', 'Mining activity cancelled successfully!');
    }

    /**
     * Delete a mining activity.
     */
    public function destroy($id)
    {
        $mining = UserMining::findOrFail($id);
        
        // If mining was active, return the invested amount to user balance
        if ($mining->status === 'active') {
            $user = $mining->user;
            $user->balance += $mining->amount_invested;
            $user->save();
            
            \Log::info("Mining deleted for user {$user->id}. Invested amount of \${$mining->amount_invested} returned to balance. New balance: \${$user->balance}");
        }

        $mining->delete();

        return redirect()->back()->with('success', 'Mining activity deleted successfully!');
    }

    /**
     * Show mining statistics.
     */
    public function statistics()
    {
        $stats = [
            'total_mining' => UserMining::count(),
            'active_mining' => UserMining::where('status', 'active')->count(),
            'completed_mining' => UserMining::where('status', 'completed')->count(),
            'cancelled_mining' => UserMining::where('status', 'cancelled')->count(),
            'suspended_mining' => UserMining::where('status', 'suspended')->count(),
            'total_invested' => UserMining::sum('amount_invested'),
            'total_mined' => UserMining::sum('total_mined'),
            'total_value' => UserMining::sum('current_value'),
        ];

        // Monthly mining data
        $dateFormat = DB::getDriverName() === 'sqlite' 
            ? 'strftime("%Y-%m", created_at)' 
            : 'DATE_FORMAT(created_at, "%Y-%m")';
            
        $monthlyData = UserMining::select(
                DB::raw("{$dateFormat} as month"),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount_invested) as total_invested'),
                DB::raw('SUM(total_mined) as total_mined')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top mining plans
        $topPlans = UserMining::select('plan_id')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('SUM(amount_invested) as total_invested')
            ->with('plan:id,name')
            ->groupBy('plan_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.mining.statistics', compact('stats', 'monthlyData', 'topPlans'));
    }
}
