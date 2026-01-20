<?php

namespace App\Listeners;

use App\Events\WithdrawalSubmitted;
use App\Services\NotificationService;

class SendWithdrawalSubmittedNotification
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
    public function handle(WithdrawalSubmitted $event): void
    {
        // Check if notification already exists to prevent duplicates
        $existingNotification = \App\Models\UserNotification::where('user_id', $event->user->id)
            ->where('type', 'withdrawal_submitted')
            ->where('data->withdrawal_id', $event->withdrawal->id)
            ->first();

        if ($existingNotification) {
            \Log::info('Withdrawal notification already exists, skipping duplicate', [
                'withdrawal_id' => $event->withdrawal->id,
                'user_id' => $event->user->id
            ]);
            return;
        }

        // Create notification for the user who submitted the withdrawal
        $this->notificationService->createNotification(
            $event->user,
            'withdrawal_submitted',
            'Withdrawal Submitted',
            "Your withdrawal request of $" . number_format($event->amount, 2) . " from your " . ucfirst(str_replace('_', ' ', $event->fromAccount)) . " account has been submitted and is pending approval.",
            [
                'amount' => $event->amount,
                'payment_method' => $event->paymentMethod,
                'from_account' => $event->fromAccount,
                'withdrawal_id' => $event->withdrawal->id,
                'status' => 'pending',
                'type' => 'withdrawal_submitted'
            ]
        );

        // Log the withdrawal submission for admin tracking
        \Log::info('Withdrawal submitted', [
            'user_id' => $event->user->id,
            'amount' => $event->amount,
            'payment_method' => $event->paymentMethod,
            'from_account' => $event->fromAccount,
            'withdrawal_id' => $event->withdrawal->id
        ]);
    }
}
