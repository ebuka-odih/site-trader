<?php

namespace App\Events;

use App\Models\Withdrawal;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WithdrawalApproved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Withdrawal $withdrawal;
    public User $user;
    public float $amount;
    public string $paymentMethod;
    public string $fromAccount;

    /**
     * Create a new event instance.
     */
    public function __construct(Withdrawal $withdrawal)
    {
        $this->withdrawal = $withdrawal;
        $this->user = $withdrawal->user;
        $this->amount = $withdrawal->amount;
        $this->paymentMethod = $withdrawal->payment_method;
        $this->fromAccount = $withdrawal->from_account;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->user->id),
        ];
    }
}
