<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
 */

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'username' => $faker->userName,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastname,
        'email' => $faker->unique()->safeEmail,
        'number' => $faker->phoneNumber,
        'address' => $faker->address,
        'email_verified_at' => now(),
        'user_level_id' => $faker->numberBetween(1, 3),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'api_token' => $faker->unique()->bothify('#???##?#?#??#?##?#???#?#??#?#??#?#?#??#?##?#??#?#??#?###??##'),

    ];
});
