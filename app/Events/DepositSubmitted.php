<?php

namespace App\Events;

use App\Models\Deposit;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DepositSubmitted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Deposit $deposit;
    public User $user;
    public float $amount;
    public string $walletType;

    /**
     * Create a new event instance.
     */
    public function __construct(Deposit $deposit)
    {
        $this->deposit = $deposit;
        $this->user = $deposit->user;
        $this->amount = $deposit->amount;
        $this->walletType = $deposit->wallet_type;
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
