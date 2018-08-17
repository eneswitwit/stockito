<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Creative::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'company' => $faker->company,
    ];
});
