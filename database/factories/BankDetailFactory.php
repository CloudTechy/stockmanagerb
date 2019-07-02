<?php

use App\BankDetail;
use Faker\Generator as Faker;

$factory->define(App\BankDetail::class, function (Faker $faker) {
    return [
        'account_name' => $faker->name,
        'account_number' => $faker->bankAccountNumber,
        'bank' => $faker->randomElement($array = array('First', 'Access', 'GTB', 'Diamond', 'Polaris', 'UBA', 'Zenith', 'Fidelity')),

    ];
});
