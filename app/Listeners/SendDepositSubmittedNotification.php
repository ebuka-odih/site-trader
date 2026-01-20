<?php

namespace App\Listeners;

use App\Events\DepositSubmitted;
use App\Services\NotificationService;

class SendDepositSubmittedNotification
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
    public function handle(DepositSubmitted $event): void
    {
        // Check if notification already exists to prevent duplicates
        $existingNotification = \App\Models\UserNotification::where('user_id', $event->user->id)
            ->where('type', 'deposit_submitted')
            ->where('data->deposit_id', $event->deposit->id)
            ->first();

        if ($existingNotification) {
            \Log::info('Deposit notification already exists, skipping duplicate', [
                'deposit_id' => $event->deposit->id,
                'user_id' => $event->user->id
            ]);
            return;
        }

        // Create notification for the user who submitted the deposit
        $this->notificationService->createNotification(
            $event->user,
            'deposit_submitted',
            'Deposit Submitted',
            "Your deposit of $" . number_format($event->amount, 2) . " to your " . ucfirst($event->walletType) . " wallet has been submitted and is pending approval.",
            [
                'amount' => $event->amount,
                'wallet_type' => $event->walletType,
                'deposit_id' => $event->deposit->id,
                'status' => 'pending',
                'type' => 'deposit_submitted'
            ]
        );

        // Also create a notification for admins (optional - you can add admin notification logic here)
        // For now, we'll just log it
        \Log::info('Deposit submitted', [
            'user_id' => $event->user->id,
            'amount' => $event->amount,
            'wallet_type' => $event->walletType,
            'deposit_id' => $event->deposit->id
        ]);
    }
}
