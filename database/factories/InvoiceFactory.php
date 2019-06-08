<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\Invoice::class, function (Faker $faker) {
    $user = User::inRandomOrder()->get()->first()->id;

    return [

        'user_id' => $user,
        'amount' => $faker->numberBetween(1000, 30000),
    ];
});
