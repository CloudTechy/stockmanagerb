<?php

use Faker\Generator as Faker;

$factory->define(App\UserLevel::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->randomElement($array = array('super-administrator', 'administrator', 'normal')),
        'role' => 'manages the website',
    ];
});
