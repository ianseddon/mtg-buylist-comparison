<?php

namespace Tests\Feature\Scraping;

use Tests\TestCase;
use App\Scraping\CardKingdomScraper;
use App\Scraping\BuyOrderNotFoundException;
use App\Scraping\AmbiguousResultsException;

class CardKingdomScraperTest extends TestCase
{
    protected function getScraper()
    {
        return new CardKingdomScraper;
    }

    public function test_it_can_retrieve_a_card_price()
    {
        $buyOrder = $this->getScraper()
            ->cardName('Bloodstained Mire')
            ->cardSet('Onslaught')
            ->getPrice();

        $this->assertEquals(10, $buyOrder->buying);
        $this->assertRegexp('/17\.50/', $buyOrder->price);
    }

    public function test_it_handles_card_not_found()
    {
        $this->expectException(BuyOrderNotFoundException::class);

        $buyOrder = $this->getScraper()
            ->cardName('Flibbity Gibbit')
            ->cardSet('Return to Ravnica')
            ->getPrice();
    }

    public function test_it_handles_ambiguous_results()
    {
        $this->expectException(AmbiguousResultsException::class);

        $this->getScraper()
            ->cardName('Guildgate')
            ->cardSet('Gatecrash')
            ->foil()
            ->getPrice();
    }
}
