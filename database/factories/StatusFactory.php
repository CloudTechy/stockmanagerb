<?php

use Faker\Generator as Faker;

$factory->define(App\Status::class, function (Faker $faker) {

    return [
        'type' => $faker->unique()->randomElement($array = array('paid', 'pending', 'not-paid')),
    ];
});
