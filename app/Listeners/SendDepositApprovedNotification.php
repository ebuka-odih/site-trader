<?php

namespace App\Listeners;

use App\Events\DepositApproved;
use App\Services\NotificationService;

class SendDepositApprovedNotification
{
    protected NotificationService $notificationService;

    /**
     * Create the event listener.
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event.
     */
    public function handle(DepositApproved $event): void
    {
        // Check if notification already exists to prevent duplicates
        $existingNotification = \App\Models\UserNotification::where('user_id', $event->user->id)
            ->where('type', 'deposit_approved')
            ->where('data->deposit_id', $event->deposit->id)
            ->first();

        if ($existingNotification) {
            \Log::info('Deposit approval notification already exists, skipping duplicate', [
                'deposit_id' => $event->deposit->id,
                'user_id' => $event->user->id
            ]);
            return;
        }

        // Create notification for the user whose deposit was approved
        $this->notificationService->createNotification(
            $event->user,
            'deposit_approved',
            'Deposit Approved',
            "Your deposit of $" . number_format($event->amount, 2) . " has been approved and credited to your " . ucfirst($event->walletType) . " wallet.",
            [
                'amount' => $event->amount,
                'wallet_type' => $event->walletType,
                'deposit_id' => $event->deposit->id,
                'status' => 'approved',
                'type' => 'deposit_approved'
            ]
        );

        // Log the approval for admin tracking
        \Log::info('Deposit approved', [
            'user_id' => $event->user->id,
            'amount' => $event->amount,
            'wallet_type' => $event->walletType,
            'deposit_id' => $event->deposit->id
        ]);
    }
}
