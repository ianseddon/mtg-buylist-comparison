<?php

namespace Tests\Feature\Scraping;

use App\Scraping\AbuGamesScraper;

class AbuGamesScraperTest extends ScraperTest
{
    protected function getScraper()
    {
        return new AbuGamesScraper;
    }
}
