<?php

use Faker\Generator as Faker;

$factory->define(App\Customer::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'number' => $faker->phoneNumber,
        'email' => $faker->safeEmail,
        'notes' => $faker->sentence,
        'owing' => $faker->randomNumber(4),
        'user_id' => $faker->numberBetween(1, 5),
    ];
});
