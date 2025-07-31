<?php

use Faker\Generator as Faker;

$factory->define(App\Supplier::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->company,
        'address' => $faker->address,
        'city' => $faker->city,
        'country' => $faker->country,
        'number' => $faker->phoneNumber,
        'email' => $faker->safeEmail,
        'is_stock_available' => $faker->boolean($chanceOfGettingTrue = 90),
        'owed' => $faker->randomNumber(6),
        'user_id' => $faker->numberBetween(1, 5),
    ];
});
