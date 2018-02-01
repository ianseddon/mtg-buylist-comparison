<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Reference\Set::class, function (Faker $faker) {
    return [
        'name' => $faker->words('3', true),
        'code' => strtoupper($faker->lexify('???')),
    ];
});
