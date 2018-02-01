<?php

namespace Tests\Feature\Import;

use Tests\TestCase;
use App\Jobs\Import\ImportCardsInSet;
use App\Models\Reference\Set;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImportCardsInSetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_creates_cards_from_data()
    {
        $set = factory(Set::class)->create();
        $numberOfCards = 2;
        $cards = $this->makeSampleImportedCards($numberOfCards);

        dispatch_now(new ImportCardsInSet($set, $cards));

        $this->assertCount($numberOfCards, $set->cards);
    }

    /** @test */
    public function test_it_skip_cards_without_multiverse_id()
    {
        $set = factory(Set::class)->create();
        $cards = $this->makeSampleImportedCards(1, ['multiverseid' => null]);

        dispatch_now(new ImportCardsInSet($set, $cards));

        $this->assertEmpty($set->cards);
    }

    /** @test */
    public function test_it_doesnt_create_duplicates_with_same_multiverse_id()
    {
        $set = factory(Set::class)->create();
        $cards = $this->makeSampleImportedCards(2, ['multiverseid' => 1]);

        dispatch_now(new ImportCardsInSet($set, $cards));

        $this->assertCount(1, $set->cards);
    }

    /** @test */
    public function test_it_updates_changed_cards()
    {
        $set = factory(Set::class)->create();
        $cards = $this->makeSampleImportedCards(1);
        dispatch_now(new ImportCardsInSet($set, $cards));

        $newName = 'Name Changed!';
        $cards[0]['name'] = $newName;
        dispatch_now(new ImportCardsInSet($set, $cards));

        $this->assertCount(1, $set->cards);
        $this->assertEquals($newName, $set->cards->first()->name);
    }

    /**
     * Create test card data that mirrors the
     * expected source JSON structure
     */
    protected function makeSampleImportedCards($count = 1, $attributes = [])
    {
        $cardStub = file_get_contents(resource_path('stubs/regularCard.json'));
        $cardData = json_decode($cardStub, true);

        // Create cards.
        $cards = [];
        for ($i = 0; $i < $count; $i++) {
            $card = $cardData;
            $card['multiverseid'] = $i;

            $cards[] = array_merge($card, $attributes);
        }

        return $cards;
    }
}
