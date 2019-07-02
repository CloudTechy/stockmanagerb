<?php

use Faker\Generator as Faker;

$factory->define(App\Size::class, function (Faker $faker) {

    return [
        'name' => $faker->unique()->randomElement($array = array('small', 'medium', 'big')),
        'description' => $faker->sentence,
    ];
});
