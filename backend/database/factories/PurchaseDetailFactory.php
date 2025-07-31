<?php

use Faker\Generator as Faker;

$factory->define(App\PurchaseDetail::class, function (Faker $faker) {
    return [
        'product' => 'Prd' . $faker->text($maxNbChars = 5) . $faker->word . $faker->text($maxNbChars = 6),
        'brand' => $faker->randomElement($array = array('ibeto', 'kymco')),
        'quantity' => $faker->randomNumber(2),
        'price' => $faker->randomNumber(3),
        'category' => $faker->randomElement($array = array('Tyre', 'Battery')),
        'percent_sale' => $faker->numberBetween(0, 100),
        'sale_price' => $faker->randomNumber(3),
        'pku' => $faker->randomElement($array = array('kG', 'CM', 'M', 'L', 'tonne(s)', 'g', 'yard(s)', 'inche(s)')),
        'size' => $faker->randomElement($array = array('small', 'medium', 'big')),

    ];
});
