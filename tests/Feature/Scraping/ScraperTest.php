<?php

namespace Tests\Feature\Scraping;

use Tests\TestCase;
use App\Scraping\BuyOrderNotFoundException;
use App\Scraping\AmbiguousResultsException;

abstract class ScraperTest extends TestCase
{
    /**
     * Get the scraper instance.
     *
     * @return void
     */
    abstract protected function getScraper();

    public function test_it_can_retrieve_a_card_price()
    {
        $buyOrder = $this->getScraper()
            ->cardName('Bloodstained Mire')
            ->cardSet('Onslaught')
            ->getBuyOrder();

        $this->assertRegexp('/\d+\.\d+/', $buyOrder->price);
    }

    public function test_it_handles_card_not_found()
    {
        $this->expectException(BuyOrderNotFoundException::class);

        $buyOrder = $this->getScraper()
            ->cardName('Flibbity Gibbit')
            ->cardSet('Return to Ravnica')
            ->getBuyOrder();
    }

    public function test_it_handles_ambiguous_results()
    {
        $this->expectException(AmbiguousResultsException::class);

        $this->getScraper()
            ->cardName('Guildgate')
            ->cardSet('Gatecrash')
            ->foil()
            ->getBuyOrder();
    }
}
