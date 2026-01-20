<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Trade;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DataExportController extends Controller
{
    public function index()
    {
        return view('admin.export.index');
    }

    public function exportUsers()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users_' . date('Y-m-d_H-i-s') . '.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Role', 'Status', 'Balance', 'Trading Balance', 'Profit', 'Created At']);

            User::where('role', 'user')->chunk(100, function ($users) use ($file) {
                foreach ($users as $user) {
                    fputcsv($file, [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->phone,
                        $user->role,
                        $user->status,
                        $user->balance,
                        $user->trading_balance,
                        $user->profit,
                        $user->created_at,
                    ]);
                }
            });

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportDeposits()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="deposits_' . date('Y-m-d_H-i-s') . '.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'User', 'Email', 'Amount', 'Method', 'Status', 'TXID', 'Created At']);

            Deposit::with('user')->chunk(100, function ($deposits) use ($file) {
                foreach ($deposits as $deposit) {
                    fputcsv($file, [
                        $deposit->id,
                        $deposit->user->name ?? 'N/A',
                        $deposit->user->email ?? 'N/A',
                        $deposit->amount,
                        $deposit->method,
                        $this->getDepositStatusText($deposit->status),
                        $deposit->txid,
                        $deposit->created_at,
                    ]);
                }
            });

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportWithdrawals()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="withdrawals_' . date('Y-m-d_H-i-s') . '.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'User', 'Email', 'Amount', 'Method', 'Status', 'Address', 'Created At']);

            Withdrawal::with('user')->chunk(100, function ($withdrawals) use ($file) {
                foreach ($withdrawals as $withdrawal) {
                    fputcsv($file, [
                        $withdrawal->id,
                        $withdrawal->user->name ?? 'N/A',
                        $withdrawal->user->email ?? 'N/A',
                        $withdrawal->amount,
                        $withdrawal->method,
                        $this->getWithdrawalStatusText($withdrawal->status),
                        $withdrawal->address,
                        $withdrawal->created_at,
                    ]);
                }
            });

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportTrades()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="trades_' . date('Y-m-d_H-i-s') . '.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'User', 'Email', 'Pair', 'Action', 'Amount', 'Leverage', 'PNL', 'Status', 'Duration', 'Created At']);

            Trade::with(['user', 'trade_pair'])->chunk(100, function ($trades) use ($file) {
                foreach ($trades as $trade) {
                    fputcsv($file, [
                        $trade->id,
                        $trade->user->name ?? 'N/A',
                        $trade->user->email ?? 'N/A',
                        $trade->trade_pair->name ?? 'N/A',
                        strtoupper($trade->action_type),
                        $trade->amount,
                        $trade->leverage,
                        $trade->pnl ?? 0,
                        ucfirst($trade->status),
                        $trade->duration,
                        $trade->created_at,
                    ]);
                }
            });

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportMining()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="mining_' . date('Y-m-d_H-i-s') . '.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'User', 'Email', 'Amount', 'Earnings', 'Status', 'Created At']);

            \App\Models\UserMining::with('user')->chunk(100, function ($minings) use ($file) {
                foreach ($minings as $mining) {
                    fputcsv($file, [
                        $mining->id,
                        $mining->user->name ?? 'N/A',
                        $mining->user->email ?? 'N/A',
                        $mining->amount,
                        $mining->earnings,
                        $mining->status,
                        $mining->created_at,
                    ]);
                }
            });

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportStaking()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="staking_' . date('Y-m-d_H-i-s') . '.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'User', 'Email', 'Amount', 'Earnings', 'Status', 'Created At']);

            \App\Models\UserStaking::with('user')->chunk(100, function ($stakings) use ($file) {
                foreach ($stakings as $staking) {
                    fputcsv($file, [
                        $staking->id,
                        $staking->user->name ?? 'N/A',
                        $staking->user->email ?? 'N/A',
                        $staking->amount,
                        $staking->earnings,
                        $staking->status,
                        $staking->created_at,
                    ]);
                }
            });

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    private function getDepositStatusText($status)
    {
        switch ($status) {
            case 0: return 'Pending';
            case 1: return 'Approved';
            case 2: return 'Declined';
            case 3: return 'Reviewing';
            default: return 'Unknown';
        }
    }

    private function getWithdrawalStatusText($status)
    {
        switch ($status) {
            case 0: return 'Pending';
            case 1: return 'Approved';
            case 2: return 'Rejected';
            default: return 'Unknown';
        }
    }
}
