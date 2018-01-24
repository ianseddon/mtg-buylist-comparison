<?php

namespace App\Scraping;

use Symfony\Component\DomCrawler\Crawler;
use App\BuyOrder;

class ChannelFireballScraper extends Scraper
{
    protected $searchEndpoint = 'http://store.channelfireball.com/advanced_search';
    protected $searchQueryParams = [
        'utf8' => '✓',
        'buylist_mode' => 1
    ];

    /**
     * Get the URL to search for the given card.
     *
     * @return string
     */
    protected function getSearchUrl()
    {
        $setId = $this->getSetIdFromName($this->cardSet);

        $queryParams = array_merge($this->searchQueryParams, [
            'search[fuzzy_search]' => $this->cardName,
            'search[category_ids_with_descendants][]' => $setId,
            'search[with_descriptor_values][256][]' => $this->cardIsFoil ? 'Foil' : 'Regular',
        ]);
        $queryString = http_build_query($queryParams);

        return $this->searchEndpoint . '?' . $queryString;
    }

    /**
     * Determine whether the card searched for was not found.
     *
     * @param Crawler $crawler
     * @return boolean
     */
    protected function cardNotFound(Crawler $crawler)
    {
        return $crawler->filter('#main-content .no-product')->count() != 0;
    }

    /**
     * Determine whether the card searched for could not
     * be identified from the results.
     *
     * @param Crawler $crawler
     * @return boolean
     */
    protected function resultsAreAmbiguous(Crawler $crawler)
    {
        return $this->getBuyOrders($crawler)->count() != 1;
    }

    /**
     * Extract BuyOrders from the search results.
     *
     * @param Crawler $crawler
     * @return BuyOrder[]
     */
    protected function extractBuyOrders(Crawler $crawler)
    {
        $results = $this->getBuyOrders($crawler);

        return $results->each(function (Crawler $node, $index) {
            return $this->extractBuyOrder($node, $index);
        });
    }

    protected function extractBuyOrder(Crawler $item, $index)
    {
        $productInfo = $item->filter('.product-info-row')->first();

        $price = str_replace('$', '', trim($productInfo->filter('.variant-pricing')->first()->text()));
        $buying = str_replace('wanted: ', '', trim($productInfo->filter('.variant-stock')->first()->text()));

        return new BuyOrder([
            'price' => $price,
            'card_name' => $this->cardName,
            'card_set' => $this->cardSet,
            'foil' => $this->cardIsFoil,
            'buying' => $buying,
        ]);
    }

    protected function getBuyOrders(Crawler $crawler)
    {
        return $crawler->filter('#main-content .global-list-container article');
    }

    protected function getSetIdFromName($setName)
    {
        if (isset($this->setNamesToIds[$setName])) {
            return $this->setNamesToIds[$setName];
        } else {
            throw new \BadMethodCallException('Could not lookup set ID for: "' . $setName . '"');
        }
    }

    protected $setNamesToIds = [
        'Ixalan Block' => 6313,
        'Ixalan' => 6323,
        'Rivals of Ixalan' => 7233,
        'Amonkhet Block' => 6133,
        'Hour of Devastation' => 6233,
        'Amonkhet' => 6143,
        'Kaladesh Block' => 5773,
        'Kaladesh' => 5783,
        'Aether Revolt' => 5923,
        'Shadows over Innistrad Block' => 5583,
        'Shadows over Innistrad' => 5593,
        'Eldritch Moon' => 5683,
        'Battle for Zendikar Block' => 5383,
        'Battle for Zendikar' => 5393,
        'Oath of the Gatewatch' => 5533,
        'Khans of Tarkir Block' => 4753,
        'Khans of Tarkir' => 4763,
        'Fate Reforged' => 4993,
        'Dragons of Tarkir' => 5083,
        'Theros Block' => 714,
        'Theros' => 715,
        'Born of the Gods' => 883,
        'Journey into Nyx' => 1233,
        'Return to Ravnica Block' => 664,
        'Return to Ravnica' => 665,
        'Gatecrash' => 674,
        "Dragon's Maze" => 709,
        'Innistrad Block' => 615,
        'Innistrad' => 616,
        'Dark Ascension' => 643,
        'Avacyn Restored' => 645,
        'Scars of Mirrodin Block' => 562,
        'Scars of Mirrodin' => 564,
        'Mirrodin Besieged' => 580,
        'New Phyrexia' => 590,
        'Zendikar Block' => 317,
        'Zendikar' => 321,
        'Worldwake' => 514,
        'Rise of the Eldrazi' => 532,
        'Shards of Alara Block' => 207,
        'Shards of Alara' => 209,
        'Conflux' => 221,
        'Alara Reborn' => 271,
        'Shadowmoor Block' => 138,
        'Shadowmoor' => 144,
        'Eventide' => 146,
        'Lorwyn Block' => 90,
        'Lorwyn' => 91,
        'Morningtide' => 136,
        'Time Spiral Block' => 16,
        'Time Spiral' => 18,
        'TimeShifted' => 93,
        'Planar Chaos' => 17,
        'Future Sight' => 15,
        'Ravnica Block' => 19,
        'Ravnica' => 22,
        'Guildpact' => 21,
        'Dissension' => 20,
        'Kamigawa Block' => 23,
        'Champions' => 26,
        'Betrayers' => 25,
        'Saviors' => 24,
        'Mirrodin Block' => 27,
        'Mirrodin' => 30,
        'Darksteel' => 29,
        'Fifth Dawn' => 28,
        'Modern Core Sets' => 690,
        'Magic Origins' => 5253,
        'Magic 2015' => 4703,
        'Magic 2014' => 713,
        'Magic 2013' => 657,
        'Magic 2012' => 596,
        'Magic 2011' => 552,
        'Magic 2010' => 279,
        '10th Edition' => 76,
        '9th Edition' => 75,
        '8th Edition' => 74,
        'Onslaught Block' => 31,
        'Onslaught' => 34,
        'Legions' => 33,
        'Scourge' => 32,
        'Odyssey Block' => 35,
        'Odyssey' => 38,
        'Torment' => 37,
        'Judgment' => 36,
        'Invasion Block' => 39,
        'Invasion' => 42,
        'Planeshift' => 41,
        'Apocalypse' => 40,
        'Masques Block' => 43,
        'Mercadian Masques' => 46,
        'Nemesis' => 45,
        'Prophecy' => 44,
        'Saga Block' => 47,
        "Urza's Saga" => 50,
        "Urza's Legacy" => 49,
        "Urza's Destiny" => 48,
        'Tempest Block' => 51,
        'Tempest' => 54,
        'Stronghold' => 53,
        'Exodus' => 52,
        'Mirage Block' => 55,
        'Mirage' => 58,
        'Visions' => 57,
        'Weatherlight' => 56,
        'Ice Age Block' => 14,
        'Ice Age' => 10,
        'Alliances' => 9,
        'ColdSnap' => 11,
        'Promotional Cards' => 108,
        'Promos: Apac Lands' => 110,
        'Promos: Arena' => 131,
        'Promos: Book Inserts' => 119,
        'Promos: Buy a Box' => 584,
        'Promos: Euro Lands' => 115,
        'Promos: FNM' => 129,
        'Promos: Game Day' => 572,
        'Promos: Grand Prix' => 669,
        'Promos: Guru Lands' => 117,
        "Promos: Hero's Path" => 5433,
        'Promos: Intro/Clash Pack' => 5343,
        'Promos: JSS' => 127,
        'Promos: Judge Rewards' => 123,
        'Promos: Miscellaneous' => 111,
        'Promos: MPR' => 125,
        'Promos: MPS Lands' => 646,
        'Promos: Release' => 121,
        'Promos: Tokens' => 113,
        "Promos: Ugin's Fate" => 5443,
        'Promos: WPN' => 668,
        'Old Expansions' => 59,
        'Arabian Nights' => 64,
        'Antiquities' => 63,
        'Legends' => 62,
        'The Dark' => 61,
        'Fallen Empires' => 60,
        'Homelands' => 107,
        'Core Sets' => 65,
        '7th Edition' => 73,
        '6th Edition' => 72,
        '5th Edition' => 71,
        '4th Edition' => 70,
        '3rd Edition/Revised' => 69,
        '3rd Edition (Foreign Black Border)' => 637,
        'Unlimited' => 68,
        'Beta' => 67,
        'Alpha' => 66,
        'Premium Deck Series' => 496,
        'PDS: Fire &amp; Lightning' => 574,
        'PDS: Slivers' => 494,
        'PDS: Graveborn' => 622,
        'From the Vault' => 587,
        'From the Vault: Dragons' => 299,
        'From the Vault: Exiled' => 297,
        'From the Vault: Legends' => 613,
        'From the Vault: Realms' => 663,
        'From the Vault: Relics' => 566,
        'From the Vault: Twenty' => 718,
        'From the Vault: Annihilation' => 4723,
        'From the Vault: Angels' => 5353,
        'From the Vault: Lore' => 5763,
        'From the Vault: Transform' => 7203,
        'Duel Decks' => 504,
        'Duel Decks: Mind vs. Might' => 6103,
        'Duel Decks: Nissa vs. Ob Nixilis' => 5753,
        'Duel Decks: Blessed vs. Cursed' => 5573,
        'Duel Decks: Zendikar vs. Eldrazi' => 5373,
        'Duel Decks: Elspeth vs. Kiora' => 5023,
        'Duel Decks Anthology' => 4973,
        'Duel Decks: Speed vs. Cunning' => 4743,
        'Duel Decks: Jace vs. Vraska' => 1163,
        'Duel Decks: Heroes vs. Monsters' => 719,
        'Duel Decks: Sorin vs. Tibalt' => 706,
        'Duel Decks: Izzet vs. Golgari' => 662,
        'Duel Decks: Venser vs. Koth' => 644,
        'Duel Decks: Ajani vs. Nicol Bolas' => 614,
        'Duel Decks: Knights vs. Dragons' => 589,
        'Duel Decks: Elspeth vs. Tezzeret' => 560,
        'Duel Decks: Jace vs. Chandra' => 301,
        'Duel Decks: Phyrexia vs. The Coalition' => 526,
        'Duel Decks: Garruk vs. Liliana' => 502,
        'Duel Decks: Divine vs. Demonic' => 303,
        'Duel Decks: Elves vs. Goblins' => 309,
        'Duel Decks: Merfolk vs. Goblins' => 7213,
        'Special Editions' => 77,
        'Archenemy' => 570,
        'Archenemy: Nicol Bolas' => 6253,
        'Chronicles' => 88,
        'Coldsnap Theme Deck Reprints' => 658,
        'Commander 2011' => 597,
        'Commander Anthology' => 6273,
        'Commander 2013' => 720,
        'Commander 2014' => 4833,
        'Commander 2015' => 5453,
        'Commander 2016' => 5863,
        'Commander 2017 Edition' => 6303,
        "Commander's Arsenal" => 675,
        'Conspiracy' => 1293,
        'Conspiracy: Take the Crown' => 5743,
        'Duels of the Planeswalkers' => 556,
        'Eternal Masters' => 5673,
        'Iconic Masters' => 7013,
        'Modern Event Deck' => 1273,
        'Modern Masters' => 712,
        'Modern Masters 2015' => 5213,
        'Modern Masters 2017' => 6013,
        'Planechase (2009 Edition)' => 512,
        'Planechase (2012 Edition)' => 655,
        'Planechase Anthology' => 5913,
        'Portal 1' => 78,
        'Portal Second Age' => 79,
        'Portal 3 Kingdoms' => 80,
        'Starter 1999' => 81,
        'Unglued' => 89,
        'Unhinged' => 82,
        'Unstable' => 7223,
        'Masterpiece Series' => 5803,
        'Amonkhet Invocations' => 6123,
        'Kaladesh Inventions' => 5813,
        'Zendikar Expeditions' => 5413,
    ];
}
