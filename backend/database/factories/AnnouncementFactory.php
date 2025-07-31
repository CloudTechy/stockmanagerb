<?php

use Faker\Generator as Faker;

$factory->define(App\Announcement::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(1, 5),
        'is_active' => $faker->boolean($chanceOfGettingTrue = 30),
        'topic' => $faker->sentence,
        'message' => $faker->paragraph,
        'auto_publish' => $faker->boolean($chanceOfGettingTrue = 30),
        'published' => $faker->boolean($chanceOfGettingTrue = 30),
        'date_start' => $faker->dateTimeBetween($startDate = '-1 month', $endDate = 'now'),
        'date_end' => $faker->dateTimeBetween($startDate = 'now', $endDate = '1 month'),

    ];
});
