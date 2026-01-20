<?php

namespace App\Observers;

use App\Models\User;
use App\Services\BalanceHistoryService;

class UserObserver
{
    public function updated(User $user): void
    {
        $service = app(BalanceHistoryService::class);
        $mapping = [
            'balance' => 'wallet',
            'trading_balance' => 'trading',
            'profit' => 'pnl',
        ];

        foreach ($mapping as $attribute => $type) {
            if ($user->isDirty($attribute)) {
                $service->record($user, $type, (float) $user->{$attribute}, 'system_update', [
                    'attribute' => $attribute,
                    'changed_by' => optional(auth()->user())->id ?? 'system',
                ]);
            }
        }
    }
}
