<?php

namespace Tests\Feature\Scraping;

use App\Scraping\CardKingdomScraper;

class CardKingdomScraperTest extends ScraperTest
{
    protected function getScraper()
    {
        return new CardKingdomScraper;
    }
}
