<?php

use Faker\Generator as Faker;

$factory->define(App\Purchase::class, function (Faker $faker) {
    return [
        'supplier_id' => $faker->numberBetween(1, 20),
        'user_id' => $faker->numberBetween(1, 5),
        'comment' => $faker->sentence,
    ];
});
