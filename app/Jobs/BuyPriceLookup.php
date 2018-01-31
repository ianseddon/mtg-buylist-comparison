<?php

namespace App\Jobs;

use App\CardListItem;
use App\VendorSite;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Events\BuyOrderFound;
use App\Events\BuyOrderNotFound;
use App\BuyOrder;

class BuyPriceLookup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The card list item to find a price for.
     *
     * @var CardListItem
     */
    protected $cardListItem;

    /**
     * The vendor site to search.
     *
     * @var VendorSite
     */
    protected $vendorSite;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CardListItem $cardListItem, VendorSite $vendorSite)
    {
        $this->cardListItem = $cardListItem;
        $this->vendorSite = $vendorSite;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $cardName = $this->cardListItem->name;
        $cardSetName = $this->cardListItem->set;
        $cardIsFoil = $this->cardListItem->foil;

        logger("Checking DB buy orders for vendor site {$this->vendorSite->name} for {$this->cardListItem->name}...");

        // Try to lookup buy order for this site and card from DB.
        $dbBuyOrder = $this->vendorSite->buyOrders()
            ->where('card_name', $cardName)
            ->where('card_set', $cardSetName)
            ->where('foil', $cardIsFoil)
            ->first();

        // TODO: Check if this entry is "expired".
        if ($dbBuyOrder) {
            $this->foundBuyOrder($dbBuyOrder);

            return;
        }

        logger("Querying vendor site {$this->vendorSite->name} for {$this->cardListItem->name}...");

        try {
            $buyOrder = $this->vendorSite->getScraper()
                ->cardName($cardName)
                ->cardSet($cardSetName)
                ->foil($this->cardListItem->foil)
                ->getBuyOrder();
        } catch (\Exception $e) {
            $this->failed($e);

            return;
        }

        logger('Saving retrieved BuyOrder');

        $this->vendorSite->buyOrders()->save($buyOrder);

        $this->cardListItem->buyOrders()->attach($buyOrder);

        $this->foundBuyOrder($buyOrder);
    }

    public function failed(\Exception $e)
    {
        event(new BuyOrderNotFound($this->cardListItem, $this->vendorSite, $e->getMessage()));
    }

    protected function foundBuyOrder(BuyOrder $buyOrder)
    {
        logger('Emitting BuyOrderFound event.');

        event(new BuyOrderFound($this->cardListItem, $this->vendorSite, $buyOrder));

        logger('Buy order lookup complete!');
    }
}
