<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\CardList;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CardListItemControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_shows_all_cards_in_lists()
    {
        $list = $this->createCardList();

        $response = $this->get("/api/list/{$list->id}/cards");

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function test_it_adds_cards_to_lists()
    {
        $list = $this->createCardList();

        $card = $this->getTestCard();

        $response = $this->json('POST', "/api/list/{$list->id}/cards", $card);

        $response->assertJson($card);
        $this->assertCount(1, $list->cards);
    }

    public function test_it_shows_individual_cards_in_lists()
    {
        $list = $this->createCardList();

        $card = $list->cards()->create($this->getTestCard());

        $response = $this->get("/api/list/{$list->id}/cards/{$card->id}");

        $response->assertStatus(200);
        $response->assertJson($card->toArray());
    }

    public function test_it_updates_cards_in_lists()
    {
        $list = $this->createCardList();

        $card = $list->cards()->create($this->getTestCard());

        $card->fill([
            'name' => 'Updated',
            'quantity' => 13,
            'foil' => true,
        ]);

        $response = $this->json('PATCH', "/api/list/{$list->id}/cards/{$card->id}", $card->toArray());

        $response->assertStatus(200);
        $response->assertJson($card->toArray());
    }

    public function test_it_deletes_cards_from_lists()
    {
        $list = $this->createCardList();

        $card = $list->cards()->create($this->getTestCard());

        $response = $this->delete("/api/list/{$list->id}/cards/{$card->id}");

        $response->assertStatus(200);
        $this->assertCount(0, $list->cards);
    }

    protected function createCardList()
    {
        $list = new CardList(['name' => 'Test']);
        $list->listable_id = 1;
        $list->listable_type =  'App\User';
        $list->save();
        return $list;
    }

    protected function getTestCard(array $attributes = [])
    {
        return array_merge([
            'quantity' => 3,
            'name' => 'Bloodstained Mire',
            'set' => 'Onslaught',
            'foil' => 0,
            'condition' => 'NM/M',
        ], $attributes);
    }
}
