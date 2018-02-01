<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Reference\Card::class, function (Faker $faker) {
    return [
        'multiverse_id' => $faker->unique()->randomNumber(),
        'name' => $faker->title(),
        'set_id' => factory(App\Models\Reference\Set::class)->create()->id,
    ];
});
