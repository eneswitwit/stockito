<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\License::class, function (Faker $faker) {
    return [
        'expired_at' => $faker->dateTimeBetween('now', '+60 days'),
        'license_type' => $faker->randomElement(\App\Models\License::getLicenses())
    ];
});
