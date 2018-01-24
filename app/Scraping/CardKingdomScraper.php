<?php

namespace App\Scraping;

use App\BuyOrder;
use Symfony\Component\DomCrawler\Crawler;

class CardKingdomScraper extends Scraper
{
    protected $searchEndpoint = 'https://www.cardkingdom.com/purchasing/mtg_singles/';
    protected $searchQueryParams = [
        'filter[search]' => 'mtg_advanced'
    ];

    protected function getSearchUrl()
    {
        $queryParams = array_merge($this->searchQueryParams, [
            'filter[name]' => $this->cardName,
            'filter[category_id]' => $this->getSetIdFromName($this->cardSet),
            'filter[nonfoil]' => $this->cardIsFoil ? 0 : 1,
            'filter[foil]' => $this->cardIsFoil ? 1 : 0,
        ]);
        $queryString = http_build_query($queryParams);

        return $this->searchEndpoint . '?' . $queryString;
    }

    protected function resultsAreAmbiguous(Crawler $crawler)
    {
        return $crawler->filter('.mainListing .itemRow')->count() != 1;
    }

    protected function cardNotFound(Crawler $crawler)
    {
        if ($crawler->filter('.mainListing .itemRow.noResults')->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    protected function extractBuyOrders(Crawler $crawler)
    {
        $results = $crawler->filter('.mainListing .itemRow');

        return $results->each(function (Crawler $node, $index) {
            return $this->extractBuyOrder($node, $index);
        });
    }

    protected function extractBuyOrder(Crawler $item, $index)
    {
        // Get the price in USD.
        $price = $item->filter('.usdSellPrice')->first();
        $dollars = $price->filter('.sellDollarAmount')->first()->text();
        $cents = $price->filter('.sellCentsAmount')->first()->text();
        $usdPrice = $dollars . '.' . $cents;

        // Get the maximum number buying.
        $buying = $item->filter('ul.qtyList li:last-child')->first()->text();

        return new BuyOrder([
            'price' => $usdPrice,
            'foil' => $this->cardIsFoil,
            'card_name' => $this->cardName,
            'card_set' => $this->cardSet,
            'buying' => $buying,
        ]);
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
        'Standard' => 2779,
        'Modern' => 2864,
        '3rd Edition' => 2345,
        '4th Edition' => 2350,
        '5th Edition' => 2355,
        '6th Edition' => 2360,
        '7th Edition' => 2365,
        '8th Edition' => 2370,
        '9th Edition' => 2375,
        '10th Edition' => 2380,
        '2010 Core Set' => 2789,
        '2011 Core Set' => 2847,
        '2012 Core Set' => 2863,
        '2013 Core Set' => 2876,
        '2014 Core Set' => 2895,
        '2015 Core Set' => 2910,
        'Aether Revolt' => 3030,
        'Alara Reborn' => 2385,
        'Alliances' => 2390,
        'Alpha' => 2395,
        'Amonkhet' => 3042,
        'Anthologies' => 2400,
        'Antiquities' => 2405,
        'Apocalypse' => 2410,
        'Arabian Nights' => 2415,
        'Archenemy' => 2846,
        'Archenemy - Nicol Bo' => 3048,
        'Avacyn Restored' => 2874,
        'Battle for Zendikar' => 2953,
        'Battle Royale' => 2420,
        'Beatdown' => 2425,
        'Beta' => 2430,
        'Betrayers of Kamigaw' => 2435,
        'Born of the Gods' => 2903,
        'Card Kingdom Tokens' => 2987,
        'Champions of Kamigaw' => 2440,
        'Chronicles' => 2445,
        'Coldsnap' => 2450,
        'Collectors Ed' => 2455,
        'Collectors Ed Intl' => 2460,
        'Commander' => 2862,
        'Commander 2013' => 2902,
        'Commander 2014' => 2916,
        'Commander 2015' => 2958,
        'Commander 2016' => 2949,
        'Commander 2017' => 3055,
        'Commander Anthology' => 3047,
        "Commander's Arsenal" => 2888,
        'Conflux' => 2783,
        'Conspiracy' => 2908,
        'Conspiracy - Take th' => 2977,
        'Dark Ascension' => 2870,
        'Darksteel' => 2465,
        "Deck Builder's Toolk" => 2844,
        'Deckmaster' => 2470,
        'Dissension' => 2475,
        "Dragon's Maze" => 2892,
        'Dragons of Tarkir' => 2938,
        'DD: Ajani / Nicol Bo' => 2865,
        'DD: Anthology' => 2918,
        'DD: Blessed / Cursed' => 2969,
        'DD: Divine / Demonic' => 2480,
        'DD: Elspeth / Kiora' => 2936,
        'DD: Elspeth / Tezzer' => 2851,
        'DD: Elves / Goblins' => 2485,
        'DD: Garruk / Liliana' => 2838,
        'DD: Heroes / Monster' => 2896,
        'DD: Izzet / Golgari' => 2878,
        'DD: Jace / Chandra' => 2490,
        'DD: Jace / Vraska' => 2904,
        'DD: Knights / Dragon' => 2860,
        'DD: Merfolk / Goblin' => 3062,
        'DD: Mind / Might' => 3041,
        'DD: Nissa / Ob Nixil' => 2980,
        'DD: Phyrexia / The C' => 2841,
        'DD: Sorin / Tibalt' => 2891,
        'DD: Speed / Cunning' => 2911,
        'DD: Venser / Koth' => 2873,
        'DD: Zendikar / Eldra' => 2951,
        'Duels of the Planesw' => 2845,
        'Eldritch Moon' => 2976,
        'Eternal Masters' => 2973,
        'Eventide' => 2495,
        'Exodus' => 2500,
        'Explorers of Ixalan' => 3064,
        'Fallen Empires' => 2505,
        'Fate Reforged' => 2923,
        'Fifth Dawn' => 2510,
        'FTV: Angels' => 2952,
        'FTV: Annihilation' => 2913,
        'FTV: Dragons' => 2515,
        'FTV: Exiled' => 2815,
        'FTV: Legends' => 2868,
        'FTV: Lore' => 2979,
        'FTV: Realms' => 2883,
        'FTV: Relics' => 2850,
        'FTV: Transform' => 3065,
        'FTV: Twenty' => 2899,
        'Future Sight' => 2520,
        'Gatecrash' => 2890,
        'Gift Pack 2017' => 3063,
        'Guildpact' => 2525,
        'Homelands' => 2530,
        'Hour of Devastation' => 3051,
        'Ice Age' => 2535,
        'Iconic Masters' => 3059,
        'Innistrad' => 2866,
        'Invasion' => 2540,
        'Ixalan' => 3058,
        'Journey into Nyx' => 2905,
        'Judgment' => 2545,
        'Kaladesh' => 2983,
        'Khans of Tarkir' => 2914,
        'Legends' => 2550,
        'Legions' => 2555,
        'Lorwyn' => 2560,
        'Magic Origins' => 2950,
        'Masterpiece Series: ' => 2960,
        'Masterpiece Series: ' => 2984,
        'Masterpiece Series: ' => 3044,
        'Mercadian Masques' => 2565,
        'Mirage' => 2570,
        'Mirrodin' => 2575,
        'Mirrodin Besieged' => 2859,
        'Modern Event Deck' => 2907,
        'Modern Masters' => 2894,
        'Modern Masters 2015' => 2947,
        'Modern Masters 2017' => 3032,
        'Morningtide' => 2580,
        'Nemesis' => 2590,
        'New Phyrexia' => 2861,
        'Non-English' => 3045,
        'Oath of the Gatewatc' => 2967,
        'Odyssey' => 2595,
        'Onslaught' => 2600,
        'Planar Chaos' => 2605,
        'Planechase' => 2839,
        'Planechase 2012' => 2875,
        'Planechase Anthology' => 2989,
        'Planeshift' => 2610,
        'Portal' => 2615,
        'Portal 3K' => 2620,
        'Portal II' => 2625,
        'PDS: Fire & Lightnin' => 2854,
        'PDS: Graveborn' => 2867,
        'PDS: Slivers' => 2837,
        'Promotional' => 2630,
        'Prophecy' => 2635,
        'Ravnica' => 2640,
        'Return to Ravnica' => 2884,
        'Rise of the Eldrazi' => 2843,
        'Rivals of Ixalan' => 3076,
        'Saviors of Kamigawa' => 2645,
        'Scars of Mirrodin' => 2852,
        'Scourge' => 2650,
        'Shadowmoor' => 2655,
        'Shadows Over Innistr' => 2971,
        'Shards of Alara' => 2660,
        'Starter 1999' => 2670,
        'Starter 2000' => 2675,
        'Stronghold' => 2680,
        'Tempest' => 2685,
        'The Dark' => 2690,
        'Theros' => 2900,
        'Time Spiral' => 2695,
        'Timeshifted' => 2700,
        'Torment' => 2705,
        'Unglued' => 2710,
        'Unhinged' => 2715,
        'Unlimited' => 2720,
        'Unstable' => 3075,
        "Urza's Destiny" => 2725,
        "Urza's Legacy" => 2730,
        "Urza's Saga" => 2735,
        'Vanguard' => 2740,
        'Visions' => 2745,
        'Weatherlight' => 2750,
        'World Championships' => 2975,
        'Worldwake' => 2840,
        'Zendikar' => 2826,
    ];
}
