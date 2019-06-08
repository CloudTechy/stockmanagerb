<?php

use Faker\Generator as Faker;

$factory->define(App\Type::class, function (Faker $faker) {

    return [
        'name' => $faker->unique()->randomElement($array = array('purchase', 'order')),
    ];
});
