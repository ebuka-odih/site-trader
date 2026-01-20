<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CopiedTrade;
use App\Models\CopiedTradePnlHistory;
use Illuminate\Http\Request;

class CopiedTradeController extends Controller
{
    public function index()
    {
        $copiedTrades = CopiedTrade::with(['user', 'copy_trader', 'pnl_histories'])->orderBy('created_at','desc')->paginate(20);
        return view('admin.copy-trade.index', compact('copiedTrades'));
    }

    public function show($id)
    {
        $copiedTrade = CopiedTrade::with(['user', 'copy_trader', 'pnl_histories' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);
        
        return view('admin.copy-trade.show', compact('copiedTrade'));
    }

    public function editPnl(Request $request, $id)
    {
        $request->validate([
            'trade_count' => 'required|integer|min:0',
            'win' => 'required|integer|min:0',
            'loss' => 'required|integer|min:0',
            'pnl' => 'required|numeric',
        ]);

        $copiedTrade = CopiedTrade::findOrFail($id);
        
        // Calculate the PnL difference to add to user's account
        $oldPnL = $copiedTrade->pnl ?? 0;
        $newPnL = $request->pnl;
        $pnlDifference = $newPnL - $oldPnL;
        
        // Update the copied trade performance metrics
        $copiedTrade->update([
            'trade_count' => $request->trade_count,
            'win' => $request->win,
            'loss' => $request->loss,
            'pnl' => $request->pnl,
        ]);
        
        // Add PnL difference to user's profit field (not balance)
        // This ensures profit is tracked separately and doesn't go to balance without trace
        if ($pnlDifference != 0) {
            $user = $copiedTrade->user;
            $user->profit = ($user->profit ?? 0) + $pnlDifference;
            $user->save();
            
            // Log the profit adjustment
            \Log::info("User {$user->id} profit adjusted by \${$pnlDifference} for copied trade {$copiedTrade->id}. New profit: \${$user->profit}");
        }

        return redirect()->back()->with('success', 'Performance metrics updated successfully! ' . 
            ($pnlDifference != 0 ? "User balance adjusted by $" . number_format($pnlDifference, 2) : ""));
    }

    public function activate($id)
    {
        $copiedTrade = CopiedTrade::findOrFail($id);
        $copiedTrade->update(['status' => 1]);
        
        // Send notification to user
        $copiedTrade->user->createNotification(
            'copy_trade_started',
            'Copy Trade Started',
            "Your copy trade with {$copiedTrade->copy_trader->name} has been started by admin.",
            [
                'copied_trade_id' => $copiedTrade->id,
                'trader_name' => $copiedTrade->copy_trader->name,
                'amount' => $copiedTrade->amount
            ]
        );
        
        return redirect()->back()->with('success', 'Copied trade activated successfully!');
    }

    public function deactivate($id)
    {
        $copiedTrade = CopiedTrade::findOrFail($id);
        
        // Transfer PnL to user's profit field when stopping (not balance)
        // This ensures profit is tracked separately and doesn't go to balance without trace
        if ($copiedTrade->status == 1 && ($copiedTrade->pnl ?? 0) > 0) {
            $user = $copiedTrade->user;
            $pnlAmount = $copiedTrade->pnl;
            $user->profit = ($user->profit ?? 0) + $pnlAmount;
            $user->save();
            
            // Reset PnL to 0 after transfer
            $copiedTrade->update([
                'status' => 0,
                'pnl' => 0,
                'stopped_at' => now()
            ]);
            
            // Send notification to user
            $copiedTrade->user->createNotification(
                'copy_trade_stopped',
                'Copy Trade Stopped',
                "Your copy trade with {$copiedTrade->copy_trader->name} has been stopped. PnL of $" . number_format($pnlAmount, 2) . " has been added to your profit.",
                [
                    'copied_trade_id' => $copiedTrade->id,
                    'trader_name' => $copiedTrade->copy_trader->name,
                    'amount' => $copiedTrade->amount,
                    'pnl_transferred' => $pnlAmount
                ]
            );
            
            \Log::info("Copied trade {$id} stopped. PnL of \${$pnlAmount} added to user {$user->id} profit. New profit: \${$user->profit}");
            
            return redirect()->back()->with('success', "Copied trade stopped successfully! PnL of $" . number_format($pnlAmount, 2) . " added to user's profit.");
        } else {
            $copiedTrade->update([
                'status' => 0,
                'stopped_at' => now()
            ]);
            
            // Send notification to user
            $copiedTrade->user->createNotification(
                'copy_trade_stopped',
                'Copy Trade Stopped',
                "Your copy trade with {$copiedTrade->copy_trader->name} has been stopped.",
                [
                    'copied_trade_id' => $copiedTrade->id,
                    'trader_name' => $copiedTrade->copy_trader->name,
                    'amount' => $copiedTrade->amount
                ]
            );
            
            return redirect()->back()->with('success', 'Copied trade stopped successfully!');
        }
    }

    public function destroy($id)
    {
        $copiedTrade = CopiedTrade::findOrFail($id);
        
        // Return the amount to user's balance if the trade was active
        if ($copiedTrade->status == 1) {
            $user = $copiedTrade->user;
            $user->balance += $copiedTrade->amount;
            $user->save();
        }
        
        $copiedTrade->delete();
        
        return redirect()->back()->with('success', 'Copied trade deleted successfully!');
    }

    public function getPnlHistory($id)
    {
        $copiedTrade = CopiedTrade::findOrFail($id);
        $pnlHistories = $copiedTrade->pnl_histories()->orderBy('created_at', 'desc')->get();
        
        return response()->json($pnlHistories);
    }

    public function storePnlHistory(Request $request, $id)
    {
        $request->validate([
            'pnl' => 'required|numeric',
            'description' => 'nullable|string|max:1000',
        ]);

        $copiedTrade = CopiedTrade::findOrFail($id);
        
        $pnlHistory = CopiedTradePnlHistory::create([
            'copied_trade_id' => $copiedTrade->id,
            'pnl' => $request->pnl,
            'description' => $request->description,
        ]);

        // Update the copied trade's PNL field by adding the new PNL entry
        // This does NOT affect the user's profit field or balance
        $copiedTrade->pnl = ($copiedTrade->pnl ?? 0) + $request->pnl;
        $copiedTrade->save();

        return redirect()->back()->with('success', 'PNL history entry added successfully!');
    }

    public function updatePnlHistory(Request $request, $pnlHistoryId)
    {
        $request->validate([
            'pnl' => 'required|numeric',
            'description' => 'nullable|string|max:1000',
        ]);

        $pnlHistory = CopiedTradePnlHistory::findOrFail($pnlHistoryId);
        $copiedTrade = $pnlHistory->copied_trade;
        
        // Calculate the difference between old and new PNL
        $oldPnL = $pnlHistory->pnl;
        $newPnL = $request->pnl;
        $difference = $newPnL - $oldPnL;
        
        // Update the PNL history entry
        $pnlHistory->update([
            'pnl' => $request->pnl,
            'description' => $request->description,
        ]);

        // Update the copied trade's PNL field by applying the difference
        // This does NOT affect the user's profit field or balance
        $copiedTrade->pnl = ($copiedTrade->pnl ?? 0) + $difference;
        $copiedTrade->save();

        return redirect()->back()->with('success', 'PNL history entry updated successfully!');
    }

    public function destroyPnlHistory($pnlHistoryId)
    {
        $pnlHistory = CopiedTradePnlHistory::findOrFail($pnlHistoryId);
        $copiedTrade = $pnlHistory->copied_trade;
        
        // Subtract the PNL from the copied trade's PNL field
        // This does NOT affect the user's profit field or balance
        $copiedTrade->pnl = max(0, ($copiedTrade->pnl ?? 0) - $pnlHistory->pnl);
        $copiedTrade->save();
        
        $pnlHistory->delete();

        return redirect()->back()->with('success', 'PNL history entry deleted successfully!');
    }
}
