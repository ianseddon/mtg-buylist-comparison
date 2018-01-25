<?php

namespace App\Listeners;

use App\Events\UserCreated;

class CreateUserCollection
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $event->user->collection()->create(['name' => 'Collection']);
    }
}
