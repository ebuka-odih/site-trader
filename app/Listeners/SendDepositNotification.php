<?php

namespace App\Listeners;

use App\Events\DepositCompleted;
use App\Services\NotificationService;

class SendDepositNotification
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
    public function handle(DepositCompleted $event): void
    {
        $this->notificationService->createDepositNotification(
            $event->user,
            $event->amount,
            $event->status
        );
    }
}
