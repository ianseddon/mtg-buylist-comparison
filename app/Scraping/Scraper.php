<?php

namespace App\Scraping;

use Symfony\Component\DomCrawler\Crawler;

abstract class Scraper
{
    protected $cardSet;
    protected $cardName;
    protected $cardIsFoil = false;

    /**
     * Fluid setter for card foil status.
     *
     * @param boolean $foil
     * @return void
     */
    public function foil($foil = true)
    {
        $this->cardIsFoil = $foil;

        return $this;
    }

    /**
     * Fluid setter for card name.
     *
     * @param string $cardName
     * @return void
     */
    public function cardName(string $cardName)
    {
        $this->cardName = $cardName;

        return $this;
    }

    /**
     * Fluid setter for card set.
     *
     * @param string $cardSet
     * @return void
     */
    public function cardSet(string $cardSet)
    {
        $this->cardSet = $cardSet;

        return $this;
    }

    /**
     * Get buy order for given card parameters.
     *
     * @return App\BuyOrder
     */
    public function getBuyOrder()
    {
        $crawler = \Goutte::request('GET', $this->getSearchUrl());

        // Check if we see that the card was not found.
        if ($this->cardNotFound($crawler)) {
            throw new BuyOrderNotFoundException('A buy order for "' . $this->cardName . '" could not be found.');
        }

        // Check if the results were ambiguous.
        if ($this->resultsAreAmbiguous($crawler)) {
            throw new AmbiguousResultsException('Search results were ambiguous, expected 1 result.');
        }

        $buyOrders = $this->extractBuyOrders($crawler);

        return $buyOrders[0];
    }

    /**
     * Get the URL to search for the given card.
     *
     * @return string
     */
    abstract protected function getSearchUrl();

    /**
     * Determine whether the card searched for was not found.
     *
     * @param Crawler $crawler
     * @return boolean
     */
    abstract protected function cardNotFound(Crawler $crawler);

    /**
     * Determine whether the card searched for could not
     * be identified from the results.
     *
     * @param Crawler $crawler
     * @return boolean
     */
    abstract protected function resultsAreAmbiguous(Crawler $crawler);

    /**
     * Extract BuyOrders from the search results.
     *
     * @param Crawler $crawler
     * @return BuyOrder[]
     */
    abstract protected function extractBuyOrders(Crawler $crawler);
}
