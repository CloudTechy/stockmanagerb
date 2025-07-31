<?php

use Faker\Generator as Faker;

$factory->define(App\Brand::class, function (Faker $faker) {

    return [
        'name' => $faker->unique()->randomElement($array = array('kymco', 'ibeto')),
    ];
});
