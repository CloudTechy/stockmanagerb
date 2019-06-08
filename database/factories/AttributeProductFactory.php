<?php

use Faker\Generator as Faker;

$factory->define(App\AttributeProduct::class, function (Faker $faker) {

    return [
        'user_id' => $faker->numberBetween(1, 5),

    ];
});
