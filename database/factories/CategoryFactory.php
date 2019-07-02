<?php

use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {

    return [
        'name' => $faker->unique()->randomElement($array = array('Tyre', 'Battery')),
        'description' => $faker->sentence,
    ];
});
