<?php

use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {

    return [

        'user_id' => $faker->numberBetween(1, 5),
        'discontinued' => $faker->boolean($chanceOfGettingTrue = 20),

    ];
});
