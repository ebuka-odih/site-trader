<?php

namespace App\Http\Controllers\Admin;

use App\Events\DepositApproved;
use App\Events\WithdrawalApproved;
use App\Http\Controllers\Controller;
use App\Mail\DepositApprovalMail;
use App\Mail\ApproveWithdrawalMail;
use App\Mail\RejectWithdrawalMail;
use App\Mail\DeclineDepositMail;
use App\Models\Deposit;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    /**
     * Display all deposits with user and payment method relationships
     */
    public function deposits()
    {
        $deposits = Deposit::with(['user', 'payment_method'])
                          ->latest()
                          ->get();
        return view('admin.transactions.deposits', compact('deposits'));
    }

    /**
     * Get deposit details for modal view
     */
    public function getDepositDetails($id)
    {
        try {
            $deposit = Deposit::with(['user', 'payment_method'])
                             ->findOrFail($id);

            $html = view('admin.partials.deposit-details', compact('deposit'))->render();

            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load deposit details'
            ]);
        }
    }

    /**
     * Approve a deposit and credit user's account
     */
    public function approveDeposit($id)
    {
        try {
            $deposit = Deposit::with('user')->findOrFail($id);

            if (!in_array($deposit->status, [Deposit::STATUS_PENDING, Deposit::STATUS_IN_REVIEW])) {
                return redirect()->back()->with('error', 'Deposit cannot be approved in its current state.');
            }

            $deposit->status = Deposit::STATUS_APPROVED;
            $deposit->save();

            $this->applyWalletAdjustment($deposit->user, $deposit);

            // Create notification directly
            $deposit->user->createNotification(
                'deposit_approved',
                'Deposit Approved',
                "Your deposit of " . $deposit->user->formatAmount($deposit->amount) . " to your " . ucfirst($deposit->wallet_type) . " wallet has been approved and credited to your account.",
                [
                    'amount' => $deposit->amount,
                    'wallet_type' => $deposit->wallet_type,
                    'deposit_id' => $deposit->id,
                    'status' => 'approved'
                ]
            );

            // Send approval email to user
            try {
                Mail::to($deposit->user->email)->send(new DepositApprovalMail($deposit));
            } catch (\Exception $e) {
                \Log::error('Failed to send deposit approval email: ' . $e->getMessage());
            }

            return redirect()->back()->with('success', 'Deposit approved successfully! User account has been credited.');

        } catch (\Exception $e) {
            \Log::error('Deposit approval failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to approve deposit. Please try again.');
        }
    }

    /**
     * Decline a deposit
     */
    public function declineDeposit($id)
    {
        try {
            $deposit = Deposit::with('user')->findOrFail($id);

            if (!in_array($deposit->status, [Deposit::STATUS_PENDING, Deposit::STATUS_IN_REVIEW])) {
                return redirect()->back()->with('error', 'Deposit has already been processed.');
            }

            $deposit->status = Deposit::STATUS_DECLINED;
            $deposit->save();

            // Send decline email
            try {
                Mail::to($deposit->user->email)->send(new DeclineDepositMail($deposit));
            } catch (\Exception $e) {
                \Log::error('Failed to send deposit decline email: ' . $e->getMessage());
            }

            return redirect()->back()->with('success', 'Deposit declined successfully.');

        } catch (\Exception $e) {
            \Log::error('Deposit decline failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to decline deposit. Please try again.');
        }
    }

    /**
     * Move an approved deposit back into review and remove credited funds
     */
    public function reviewDeposit($id)
    {
        try {
            $deposit = Deposit::with('user')->findOrFail($id);

            if ($deposit->status !== Deposit::STATUS_APPROVED) {
                return redirect()->back()->with('error', 'Only approved deposits can be moved to review.');
            }

            $this->applyWalletAdjustment($deposit->user, $deposit, -1);

            $deposit->status = Deposit::STATUS_IN_REVIEW;
            $deposit->save();

            $deposit->user->createNotification(
                'deposit_review',
                'Deposit Under Review',
                "Your deposit of " . $deposit->user->formatAmount($deposit->amount) . " has been placed under review. The funds will be restored once the review is complete.",
                [
                    'amount' => $deposit->amount,
                    'wallet_type' => $deposit->wallet_type,
                    'deposit_id' => $deposit->id,
                    'status' => 'review',
                ]
            );

            return redirect()->back()->with('success', 'Deposit moved to review. Funds have been temporarily removed until it is approved again.');
        } catch (\Exception $e) {
            \Log::error('Deposit review failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to move deposit to review. Please try again.');
        }
    }

    /**
     * Delete a deposit
     */
    public function deleteDeposit($id)
    {
        try {
            $deposit = Deposit::findOrFail($id);

            // Delete proof file if it exists
            if ($deposit->proof && Storage::disk('public')->exists($deposit->proof)) {
                Storage::disk('public')->delete($deposit->proof);
            }

            $deposit->delete();

            return redirect()->back()->with('success', 'Deposit deleted successfully.');

        } catch (\Exception $e) {
            \Log::error('Deposit deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete deposit. Please try again.');
        }
    }

    /**
     * Display all withdrawals
     */
    public function withdrawal()
    {
        $withdrawal = Withdrawal::with('user')->latest()->get();
        return view('admin.transactions.withdrawal', compact('withdrawal'));
    }

    /**
     * Display all withdrawals (alias for withdrawal)
     */
    public function withdrawals()
    {
        $withdrawal = Withdrawal::with('user')->latest()->get();
        return view('admin.transactions.withdrawal', compact('withdrawal'));
    }



    /**
     * Approve a withdrawal (route model binding)
     * Deducts funds from user's account and changes status to approved
     * Can approve pending (0) or declined (2) withdrawals, but not already approved (1) ones
     */
    public function approveWithdrawal(Withdrawal $withdrawal)
    {
        try {
            // Refresh the withdrawal to get latest data
            $withdrawal->refresh();
            
            // Prevent approving already approved withdrawals (status 1)
            if ($withdrawal->status == 1) {
                return redirect()->back()->with('error', 'This withdrawal has already been approved.');
            }

            // Allow approving pending (0) or declined (2) withdrawals
            // Declined withdrawals can be re-approved since funds were never deducted
            
            // Load user relationship and refresh to get latest balance
            $user = $withdrawal->user;
            $user->refresh();
            
            $fromAccount = $withdrawal->from_account ?? 'balance';
            $amount = $withdrawal->amount;

            // Check if user has sufficient balance
            $currentBalance = $user->$fromAccount ?? 0;
            if ($currentBalance < $amount) {
                $accountName = ucfirst(str_replace('_', ' ', $fromAccount));
                return redirect()->back()->with('error', "User does not have sufficient balance in {$accountName}. Current balance: $" . number_format($currentBalance, 2) . ". Required: $" . number_format($amount, 2));
            }

            // Only deduct funds if withdrawal was not already approved
            // This prevents double-deduction if somehow the status check fails
            if ($withdrawal->status != 1) {
                // Use decrement method for atomic database operation
                $user->decrement($fromAccount, $amount);

                // Refresh user to get updated balance
                $user->refresh();
            }

            // Update withdrawal status to approved
            $withdrawal->status = 1;
            $withdrawal->save();

            \Log::info('Withdrawal approved and funds deducted', [
                'withdrawal_id' => $withdrawal->id,
                'user_id' => $user->id,
                'amount' => $amount,
                'from_account' => $fromAccount,
                'new_balance' => $user->$fromAccount
            ]);

            // Create notification directly
            $withdrawal->user->createNotification(
                'withdrawal_approved',
                'Withdrawal Approved',
                "Your withdrawal request of " . $withdrawal->user->formatAmount($withdrawal->amount) . " has been approved and will be processed shortly.",
                [
                    'amount' => $withdrawal->amount,
                    'withdrawal_id' => $withdrawal->id,
                    'status' => 'approved'
                ]
            );

            try {
                Mail::to($withdrawal->user->email)->send(new ApproveWithdrawalMail($withdrawal));
            } catch (\Exception $e) {
                \Log::error('Failed to send withdrawal approval email: ' . $e->getMessage());
            }

            return redirect()->back()->with('success', 'Withdrawal approved successfully. Funds have been deducted from user account.');

        } catch (\Exception $e) {
            \Log::error('Withdrawal approval failed: ' . $e->getMessage(), [
                'withdrawal_id' => $withdrawal->id ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to approve withdrawal. Please try again.');
        }
    }

    /**
     * Decline a withdrawal
     * Only changes status - no refund needed since funds were not deducted on request
     * Can decline pending (0) withdrawals, but not already approved (1) ones
     */
    public function declineWithdrawal($id)
    {
        try {
            $withdraw = Withdrawal::with('user')->findOrFail($id);
            $withdraw->refresh();
            
            // Prevent declining already approved withdrawals (status 1)
            if ($withdraw->status == 1) {
                return redirect()->back()->with('error', 'Cannot decline an already approved withdrawal. Please contact support if you need to reverse this transaction.');
            }

            // Allow declining pending (0) or re-declining declined (2) withdrawals
            // Only change status - funds were never deducted, so no refund needed
            $withdraw->status = 2;
            $withdraw->save();

            // Create notification directly
            $withdraw->user->createNotification(
                'withdrawal_declined',
                'Withdrawal Declined',
                "Your withdrawal request of " . $withdraw->user->formatAmount($withdraw->amount) . " has been declined.",
                [
                    'amount' => $withdraw->amount,
                    'withdrawal_id' => $withdraw->id,
                    'status' => 'declined'
                ]
            );

            try {
                Mail::to($withdraw->user->email)->send(new RejectWithdrawalMail($withdraw));
            } catch (\Exception $e) {
                \Log::error('Failed to send withdrawal decline email: ' . $e->getMessage());
            }

            return redirect()->back()->with('success', 'Withdrawal declined successfully.');

        } catch (\Exception $e) {
            \Log::error('Withdrawal decline failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to decline withdrawal. Please try again.');
        }
    }

    /**
     * Reject a withdrawal (route model binding)
     * Only changes status - no refund needed since funds were not deducted on request
     * Can reject pending (0) withdrawals, but not already approved (1) ones
     */
    public function rejectWithdrawal(Withdrawal $withdrawal)
    {
        try {
            $withdrawal->refresh();
            
            // Prevent rejecting already approved withdrawals (status 1)
            if ($withdrawal->status == 1) {
                return redirect()->back()->with('error', 'Cannot reject an already approved withdrawal. Please contact support if you need to reverse this transaction.');
            }

            // Allow rejecting pending (0) or re-rejecting declined (2) withdrawals
            // Only change status - funds were never deducted, so no refund needed
            $withdrawal->status = 2;
            $withdrawal->save();

            // Create notification directly
            $withdrawal->user->createNotification(
                'withdrawal_declined',
                'Withdrawal Declined',
                "Your withdrawal request of " . $withdrawal->user->formatAmount($withdrawal->amount) . " has been declined.",
                [
                    'amount' => $withdrawal->amount,
                    'withdrawal_id' => $withdrawal->id,
                    'status' => 'declined'
                ]
            );

            try {
                Mail::to($withdrawal->user->email)->send(new RejectWithdrawalMail($withdrawal));
            } catch (\Exception $e) {
                \Log::error('Failed to send withdrawal decline email: ' . $e->getMessage());
            }

            return redirect()->back()->with('success', 'Withdrawal declined successfully.');

        } catch (\Exception $e) {
            \Log::error('Withdrawal decline failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to decline withdrawal. Please try again.');
        }
    }

    /**
     * Apply a credit or debit to the appropriate user wallet based on the deposit type
     */
    private function applyWalletAdjustment(User $user, Deposit $deposit, float $multiplier = 1): void
    {
        $amount = round($deposit->amount * $multiplier, 2);

        $field = match ($deposit->wallet_type) {
            'holding' => 'holding_balance',
            'staking' => 'staking_balance',
            default => 'balance',
        };

        $current = $user->{$field} ?? 0;
        $user->{$field} = $current + $amount;
        $user->save();
    }
}
