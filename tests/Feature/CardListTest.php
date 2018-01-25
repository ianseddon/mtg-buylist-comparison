<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;

class CardListTest extends TestCase
{
    public function test_creating_a_user_also_creates_a_collection()
    {
        $user = factory(User::class)->create();

        $this->assertNotEmpty($user->collection);
    }
}
