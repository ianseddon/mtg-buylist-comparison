<?php

namespace Tests\Feature\Scraping;

use App\Scraping\ChannelFireballScraper;

class ChannelFireballScraperTest extends ScraperTest
{
    protected function getScraper()
    {
        return new ChannelFireballScraper;
    }
}
