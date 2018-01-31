<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\CardListItem;
use App\BuyOrder;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\VendorSite;

class BuyOrderFound implements ShouldBroadcast
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
     * The buy order retrieved for the card list item.
     *
     * @var BuyOrder
     */
    public $buyOrder;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CardListItem $cardListItem, VendorSite $vendorSite, BuyOrder $buyOrder)
    {
        $this->cardListItem = $cardListItem;
        $this->vendorSite = $vendorSite;
        $this->buyOrder = $buyOrder;
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
