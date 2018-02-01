<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CardListTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_a_user_also_creates_a_collection()
    {
        $user = factory(User::class)->create();

        $this->assertNotEmpty($user->collection);
    }
}
