<?php

use Illuminate\Database\Seeder;
use App\VendorSite;
use App\Scraping\ChannelFireballScraper;
use App\Scraping\AbuGamesScraper;
use App\Scraping\CardKingdomScraper;

class VendorSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VendorSite::create([
            'name' => 'ABU Games',
            'scraper_class' => AbuGamesScraper::class,
        ]);

        VendorSite::create([
            'name' => 'Card Kingdom',
            'scraper_class' => CardKingdomScraper::class,
        ]);

        VendorSite::create([
            'name' => 'Channel Fireball',
            'scraper_class' => ChannelFireballScraper::class,
        ]);
    }
}
