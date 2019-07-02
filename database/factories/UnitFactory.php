<?php

use App\Unit;
use Faker\Generator as Faker;

$factory->define(App\Unit::class, function (Faker $faker) {

    return [
        'name' => $faker->unique()->randomElement($array = array('kG', 'CM', 'M', 'L', 'tonne(s)', 'g', 'yard(s)', 'inche(s)')),
    ];
});
