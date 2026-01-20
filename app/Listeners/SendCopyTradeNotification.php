<?php

namespace App\Listeners;

use App\Events\CopyTradeStarted;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCopyTradeNotification
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
    public function handle(CopyTradeStarted $event): void
    {
        $this->notificationService->createCopyTradingNotification(
            $event->user,
            $event->traderName,
            $event->amount,
            'started'
        );
    }
}
