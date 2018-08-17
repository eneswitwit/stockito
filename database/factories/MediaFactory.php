<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Media::class, function (Faker $faker) {
    return [
        'origin_name' => $faker->word
    ];
});
