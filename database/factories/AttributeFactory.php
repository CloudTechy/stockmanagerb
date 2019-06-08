<?php

use Faker\Generator as Faker;

$factory->define(App\Attribute::class, function (Faker $faker) {
    return [
        'type' => $faker->unique()->randomElement($array = array('kymco', 'ibeto')),
    ];
});
