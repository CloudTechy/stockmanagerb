<?php

use Faker\Generator as Faker;

$factory->define(App\Order::class, function (Faker $faker) {
    return [
        'customer_id' => $faker->numberBetween(1, 50),
        'user_id' => $faker->numberBetween(1, 5),
        'comment' => $faker->sentence,

    ];
});
