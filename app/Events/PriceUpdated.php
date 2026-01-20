<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PriceUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $asset;
    public $newPrice;
    public $priceChange;

    /**
     * Create a new event instance.
     */
    public function __construct($asset, $newPrice, $priceChange)
    {
        $this->asset = $asset;
        $this->newPrice = $newPrice;
        $this->priceChange = $priceChange;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('price-updates'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'asset_id' => $this->asset->id,
            'symbol' => $this->asset->symbol,
            'name' => $this->asset->name,
            'type' => $this->asset->type,
            'current_price' => $this->newPrice,
            'price_change_24h' => $this->priceChange,
            'updated_at' => now()->toISOString()
        ];
    }

    /**
     * Get the broadcast event name.
     */
    public function broadcastAs(): string
    {
        return 'price.updated';
    }
}
