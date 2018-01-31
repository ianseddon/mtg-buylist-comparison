<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\CardListItem;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\VendorSite;

class BuyOrderNotFound implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The card list item that we were searching for.
     *
     * @var CardListItem
     */
    public $cardListItem;

    /**
     * The vendor site we searched.
     *
     * @var VendorSite
     */
    public $vendorSite;

    /**
     * The human-readable reason the buy order could not be retrieved.
     *
     * @var string
     */
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CardListItem $cardListItem, VendorSite $vendorSite, $message = 'Buy order could not be retrieved.')
    {
        $this->cardListItem = $cardListItem;
        $this->vendorSite = $vendorSite;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('test-channel');
        // return new PrivateChannel('channel-name');
    }
}
