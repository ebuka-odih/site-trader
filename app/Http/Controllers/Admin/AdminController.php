<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Trade;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        // User Statistics
        $totalUsers = User::where('role', 'user')->count();
        $activeUsers = User::where('role', 'user')->where('status', 'active')->count();
        $newUsersToday = User::where('role', 'user')->whereDate('created_at', today())->count();
        $newUsersThisWeek = User::where('role', 'user')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $newUsersThisMonth = User::where('role', 'user')->whereMonth('created_at', now()->month)->count();

        // Financial Statistics
        $totalDeposits = Deposit::where('status', 1)->sum('amount');
        $pendingDeposits = Deposit::where('status', 0)->sum('amount');
        $depositsToday = Deposit::where('status', 1)->whereDate('created_at', today())->sum('amount');
        $depositsThisMonth = Deposit::where('status', 1)->whereMonth('created_at', now()->month)->sum('amount');

        $totalWithdrawals = Withdrawal::where('status', 1)->sum('amount');
        $pendingWithdrawals = Withdrawal::where('status', 0)->sum('amount');
        $withdrawalsToday = Withdrawal::where('status', 1)->whereDate('created_at', today())->sum('amount');
        $withdrawalsThisMonth = Withdrawal::where('status', 1)->whereMonth('created_at', now()->month)->sum('amount');

        // Trading Statistics
        $totalTraded = Trade::where('status', 'closed')->sum('amount');
        $activeTrades = Trade::where('status', 'open')->count();
        $closedTrades = Trade::where('status', 'closed')->count();
        $tradesToday = Trade::whereDate('created_at', today())->count();
        $tradesThisMonth = Trade::whereMonth('created_at', now()->month)->count();

        // Copy Trading Statistics
        $totalCopyTraders = \App\Models\CopyTrader::count();
        $activeCopyTraders = \App\Models\CopyTrader::where('status', 'active')->count();
        $totalCopiedTrades = \App\Models\CopiedTrade::count();

        // Bot Trading Statistics
        $totalBotTrades = \App\Models\BotTrade::count();
        $activeBotTrades = \App\Models\BotTrade::where('status', 'open')->count();

        // Recent Activity
        $recentUsers = User::where('role', 'user')->latest()->take(5)->get();
        $recentDeposits = Deposit::with('user')->latest()->take(5)->get();
        $recentWithdrawals = Withdrawal::with('user')->latest()->take(5)->get();
        $recentTrades = Trade::with('user')->latest()->take(5)->get();

        // Calculate growth percentages (comparing this month to last month)
        $lastMonthUsers = User::where('role', 'user')->whereMonth('created_at', now()->subMonth()->month)->count();
        $userGrowthPercentage = $lastMonthUsers > 0 ? (($newUsersThisMonth - $lastMonthUsers) / $lastMonthUsers) * 100 : 0;

        $lastMonthDeposits = Deposit::where('status', 1)->whereMonth('created_at', now()->subMonth()->month)->sum('amount');
        $depositGrowthPercentage = $lastMonthDeposits > 0 ? (($depositsThisMonth - $lastMonthDeposits) / $lastMonthDeposits) * 100 : 0;

        $lastMonthTrades = Trade::whereMonth('created_at', now()->subMonth()->month)->count();
        $tradeGrowthPercentage = $lastMonthTrades > 0 ? (($tradesThisMonth - $lastMonthTrades) / $lastMonthTrades) * 100 : 0;

        return view('admin.dashboard', compact(
            'totalUsers', 'activeUsers', 'newUsersToday', 'newUsersThisWeek', 'newUsersThisMonth',
            'totalDeposits', 'pendingDeposits', 'depositsToday', 'depositsThisMonth',
            'totalWithdrawals', 'pendingWithdrawals', 'withdrawalsToday', 'withdrawalsThisMonth',
            'totalTraded', 'activeTrades', 'closedTrades', 'tradesToday', 'tradesThisMonth',
            'totalCopyTraders', 'activeCopyTraders', 'totalCopiedTrades',
            'totalBotTrades', 'activeBotTrades',
            'recentUsers', 'recentDeposits', 'recentWithdrawals', 'recentTrades',
            'userGrowthPercentage', 'depositGrowthPercentage', 'tradeGrowthPercentage'
        ));
    }

    public function security()
   {
       return view('admin.security');
   }

   public function resetPassword(Request $request)
    {

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['error' => 'Your current password is incorrect.']);
        }
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully!');
    }

}
