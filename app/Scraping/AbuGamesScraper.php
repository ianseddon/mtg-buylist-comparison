<?php

namespace App\Scraping;

use Symfony\Component\DomCrawler\Crawler;
use App\BuyOrder;

class AbuGamesScraper extends Scraper
{
    protected $searchEndpoint = 'https://www.abugames.com/buylist.cgi';
    protected $searchQueryParams = [
        'command' => 'search',
    ];

    protected function getSearchUrl()
    {
        $setName = $this->cardSet;
        // AbuGames separates foils by set so append 'Foil' if foil.
        if ($this->cardIsFoil) {
            $setName .= ' Foil';
        }
        $edition = $this->getSetIdFromName($setName);

        $queryParams = array_merge($this->searchQueryParams, [
            'cardname' => $this->cardName,
            'edition' => $edition,
            'style' => $this->cardIsFoil ? 'Foil' : 'Normal',
        ]);
        $queryString = http_build_query($queryParams);

        return $this->searchEndpoint . '?' . $queryString;
    }

    protected function cardNotFound(Crawler $crawler)
    {
        return $crawler->filterXPath("//*[text()[contains(.,'No items could be found')]]")->count() != 0;
    }

    protected function resultsAreAmbiguous(Crawler $crawler)
    {
        return $this->getBuyOrders($crawler)->count() > 1;
    }

    protected function getBuyOrders(Crawler $crawler)
    {
        $rows = $crawler->filter('form[name=inventoryform] table tr');

        // The only rows in the table that represent cards are ones
        // that have a link in the first column to the card page.
        $filteredRows = $rows->reduce(function (Crawler $row, $index) {
            return $row->filter('td:first-child a')->count() == 1;
        });

        return $filteredRows;
    }

    protected function extractBuyOrders(Crawler $crawler)
    {
        $results = $this->getBuyOrders($crawler);

        return $results->each(function (Crawler $node, $index) {
            return $this->extractBuyOrder($node, $index);
        });
    }

    protected function extractBuyOrder(Crawler $item, $index)
    {
        $buying = $item->filter('td:nth-child(2)')->first()->text();
        $price = str_replace('$', '', $item->filter('td:nth-child(3)')->first()->text());

        return new BuyOrder([
            'price' => $price,
            'card_name' => $this->cardName,
            'card_set' => $this->cardSet,
            'foil' => $this->cardIsFoil,
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
        'Any Edition' => 0,
        '4th Edition' => 42,
        'Introductory - 4th Edition' => 352,
        '5th Edition' => 41,
        '6th Edition' => 40,
        '7th Edition' => 39,
        '7th Edition Foil' => 62,
        '8th Edition' => 38,
        '8th Edition Foil' => 63,
        '9th Edition' => 99,
        '9th Edition Foil' => 100,
        '10th Edition' => 135,
        '10th Edition Foil' => 136,
        'Aether Revolt' => 590,
        'Aether Revolt Foil' => 591,
        'Ajani vs. Nicol Bolas' => 255,
        'Alara Reborn' => 153,
        'Alara Reborn Foil' => 155,
        'Alliances' => 28,
        'Alpha' => 46,
        'Alternate 4th Edition' => 263,
        'Amonkhet' => 598,
        'Amonkhet Foil' => 599,
        'Anthologies' => 87,
        'Antiquities' => 36,
        'Apocalypse' => 14,
        'Apocalypse Foil' => 54,
        'Arabian Nights' => 37,
        'Archenemy' => 172,
        'Archenemy Nicol Bolas' => 606,
        'Avacyn Restored' => 267,
        'Avacyn Restored Foil' => 272,
        'Battle for Zendikar' => 545,
        'Battle for Zendikar Foil' => 546,
        'Battle for Zendikar Intro Lands' => 552,
        'Battle Royale' => 88,
        'Beatdown' => 89,
        'Beta' => 45,
        'Betrayers of Kamigawa' => 95,
        'Betrayers of Kamigawa Foil' => 96,
        'Blessed vs. Cursed' => 558,
        'Born of the Gods' => 366,
        'Born of the Gods Foil' => 367,
        'Champions of Kamigawa' => 91,
        'Champions of Kamigawa Foil' => 92,
        'Chronicles' => 31,
        'Coldsnap' => 109,
        'Coldsnap Foil' => 110,
        'Coldsnap Theme Deck Reprints' => 126,
        "Collectors' Edition" => 85,
        "Collectors' Edition - International" => 86,
        'Commander' => 241,
        'Commander 2013' => 364,
        'Commander 2014' => 513,
        'Commander 2015' => 553,
        'Commander 2016' => 587,
        'Commander 2017' => 611,
        'Commander Anthology' => 605,
        'Commander - Generals' => 242,
        "Commander's Arsenal" => 310,
        "Commander's Arsenal Oversized" => 311,
        'Conflux' => 151,
        'Conflux Foil' => 152,
        'Conspiracy' => 499,
        'Conspiracy Foil' => 502,
        'Conspiracy Take the Crown' => 572,
        'Conspiracy Take the Crown Foil' => 573,
        'Dark Ascension' => 259,
        'Dark Ascension Foil' => 260,
        'Darksteel' => 73,
        'Darksteel Foil' => 76,
        'Deckmasters' => 90,
        'Dissension' => 106,
        'Dissension Foil' => 108,
        'Divine vs. Demonic' => 154,
        "Dragon's Maze" => 327,
        "Dragon's Maze Foil" => 328,
        'Dragons of Tarkir' => 523,
        'Dragons of Tarkir Foil' => 524,
        'Duel Decks Anthology' => 515,
        'Duels of the Planeswalkers' => 178,
        'Eldritch Moon' => 568,
        'Eldritch Moon Foil' => 569,
        'Elspeth vs. Kiora' => 522,
        'Elspeth vs. Tezzeret' => 205,
        'Elves vs. Goblins' => 140,
        'Emblems' => 371,
        'Error Cards' => 373,
        'Eternal Masters' => 563,
        'Eternal Masters Foil' => 564,
        'Eventide' => 145,
        'Eventide Foil' => 146,
        'Exodus' => 22,
        'Explorers of Ixalan' => 622,
        'Fallen Empires' => 32,
        'Fate Reforged' => 516,
        'Fate Reforged Foil' => 517,
        'Fifth Dawn' => 81,
        'Fifth Dawn Foil' => 82,
        'From the Vault Angels' => 542,
        'From the Vault Annihilation' => 507,
        'From the Vault Dragons' => 147,
        'From the Vault Exiled' => 159,
        'From the Vault Legends' => 252,
        'From the Vault Lore' => 577,
        'From the Vault Realms' => 302,
        'From the Vault Relics' => 202,
        'From the Vault Transform' => 623,
        'From the Vault Twenty' => 354,
        'Future Sight' => 127,
        'Future Sight Foil' => 128,
        'Game Day Battle Cards' => 365,
        'Garruk vs. Liliana' => 164,
        'Gatecrash' => 314,
        'Gatecrash Foil' => 317,
        'Guildpact' => 104,
        'Guildpact Foil' => 105,
        'Heroes vs. Monsters' => 357,
        'Homelands' => 29,
        'Hour of Devastation' => 607,
        'Hour of Devastation Foil' => 608,
        'Ice Age' => 30,
        'Iconic Masters' => 616,
        'Iconic Masters Foil' => 617,
        'Innistrad' => 253,
        'Innistrad Foil' => 254,
        'Invasion' => 15,
        'Invasion Foil' => 52,
        'Ixalan' => 612,
        'Ixalan Foil' => 613,
        'Izzet vs. Golgari' => 303,
        'Jace vs. Chandra' => 150,
        'Jace vs. Vraska' => 495,
        'Journey into Nyx' => 496,
        'Journey into Nyx Foil' => 497,
        'Judgment' => 11,
        'Judgment Foil' => 57,
        'Kaladesh' => 579,
        'Kaladesh Foil' => 580,
        'Khans of Tarkir' => 508,
        'Khans of Tarkir Foil' => 509,
        'Knights vs. Dragons' => 238,
        'Legends' => 35,
        'Legions' => 9,
        'Legions Foil' => 59,
        'Lorwyn' => 138,
        'Lorwyn Foil' => 139,
        'Magic 2010 / M10' => 157,
        'Magic 2010 / M10 Foil' => 158,
        'Magic 2011 / M11' => 173,
        'Magic 2011 / M11 Foil' => 174,
        'Magic 2012 / M12' => 243,
        'Magic 2012 / M12 Foil' => 244,
        'Magic 2013 / M13' => 276,
        'Magic 2013 / M13 Foil' => 299,
        'Magic 2014 / M14' => 338,
        'Magic 2014 / M14 Foil' => 351,
        'Magic 2015 / M15' => 503,
        'Magic 2015 / M15 Foil' => 504,
        'Magic Origins' => 535,
        'Magic Origins FOIL' => 536,
        'Magic Pro Player Cards' => 125,
        'Masterpiece Series' => 586,
        'Mercadian Masques' => 18,
        'Mercadian Masques Foil' => 49,
        'Merfolk vs. Goblins' => 618,
        'Mind vs. Might' => 602,
        'Mirage' => 27,
        'Mirrodin' => 7,
        'Mirrodin Foil' => 61,
        'Mirrodin Besieged' => 232,
        'Mirrodin Besieged Foil' => 233,
        'Modern Event Deck 2014' => 498,
        'Modern Masters' => 332,
        'Modern Masters Foil' => 333,
        'Modern Masters 2015' => 529,
        'Modern Masters 2015 Foil' => 530,
        'Modern Masters 2017' => 594,
        'Modern Masters 2017 Foil' => 595,
        'Morningtide' => 141,
        'Morningtide Foil' => 142,
        'Nemesis' => 17,
        'Nemesis Foil' => 50,
        'New Phyrexia' => 239,
        'New Phyrexia Foil' => 240,
        'Nissa vs. Ob Nixilis' => 578,
        'Oath of the Gatewatch' => 554,
        'Oath of the Gatewatch Foil' => 555,
        'Odyssey' => 13,
        'Odyssey Foil' => 55,
        'Onslaught' => 10,
        'Onslaught Foil' => 58,
        'Oversized Magic Box Toppers' => 74,
        'Phyrexia vs. The Coalition' => 171,
        'Planar Chaos' => 121,
        'Planar Chaos Foil' => 122,
        'Plane Cards' => 161,
        'Plane Cards - Anthology' => 588,
        'Planechase 2009' => 160,
        'Planechase 2012' => 275,
        'Planechase Anthology' => 589,
        'Planeshift' => 34,
        'Planeshift Foil' => 53,
        'Portal' => 64,
        'Portal Second Age' => 65,
        'Portal Three Kingdoms' => 71,
        'Premium Deck Fire and Lightning' => 231,
        'Premium Deck Graveborn' => 256,
        'Premium Deck Slivers' => 166,
        'Prerelease' => 525,
        'Prerelease Inserts' => 370,
        'Promo Cards' => 69,
        'Prophecy' => 16,
        'Prophecy Foil' => 51,
        'Ravnica: City of Guilds' => 101,
        'Ravnica: City of Guilds Foil' => 102,
        'Foreign - Renaissance' => 375,
        'Return to Ravnica' => 300,
        'Return to Ravnica Foil' => 301,
        'Revised' => 43,
        'Rise of the Eldrazi' => 169,
        'Rise of the Eldrazi Foil' => 170,
        'Rivals of Ixalan' => 624,
        'Rivals of Ixalan Foil' => 625,
        'Saviors of Kamigawa' => 97,
        'Saviors of Kamigawa Foil' => 98,
        'Scars of Mirrodin' => 203,
        'Scars of Mirrodin Foil' => 204,
        'Scheme Cards' => 175,
        'Scourge' => 8,
        'Scourge Foil' => 60,
        'Shadowmoor' => 143,
        'Shadowmoor Foil' => 144,
        'Shadows over Innistrad' => 559,
        'Shadows over Innistrad Foil' => 562,
        'Shards of Alara' => 148,
        'Shards of Alara Foil' => 149,
        'Sorin vs. Tibalt' => 322,
        'Speed vs. Cunning' => 510,
        'Starter 1999' => 66,
        'Starter 2000' => 309,
        'Starter Exclusive' => 576,
        'Stronghold' => 23,
        'Summer Magic / Edgar' => 156,
        'Tempest' => 24,
        'The Dark' => 33,
        'Theros' => 355,
        'Theros Foil' => 356,
        'Time Spiral' => 111,
        'Time Spiral Foil' => 116,
        'Time Spiral - Timeshifted' => 115,
        'Time Spiral - Timeshifted Foil' => 117,
        'Tips &amp; Tricks Cards' => 137,
        'Tokens' => 72,
        'Torment' => 12,
        'Torment Foil' => 56,
        'Unglued' => 67,
        'Unhinged' => 93,
        'Unhinged Foil' => 94,
        'Unlimited' => 44,
        'Unstable' => 620,
        'Unstable Foil' => 621,
        "Urza's Destiny" => 19,
        "Urza's Destiny Foil" => 48,
        "Urza's Legacy" => 20,
        "Urza's Legacy Foil" => 47,
        "Urza's Saga" => 21,
        'Vanguard' => 68,
        'Venser vs. Koth' => 265,
        'Visions' => 26,
        'Visions - Preview' => 353,
        'Weatherlight' => 25,
        'World Championship' => 258,
        'Worldwake' => 167,
        'Worldwake Foil' => 168,
        'Zendikar ' => 162,
        'Zendikar Foil' => 163,
        'Zendikar Intro Lands' => 165,
        'Zendikar - Expeditions' => 551,
        'Zendikar vs. Eldrazi' => 549,
        'Magic Complete Sets' => 77,
        'Graded Sets and Lots' => 270,
    ];
}
