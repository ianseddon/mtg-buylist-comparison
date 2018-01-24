<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorSite extends Model
{
    protected $fillable = ['name', 'scraper_class'];

    /**
     * Get an instance of the scraper for this site.
     *
     * @return App\Scraping\Scraper
     */
    public function getScraper()
    {
        return new $this->scraper_class;
    }
}
