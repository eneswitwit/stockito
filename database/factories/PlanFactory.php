<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Plan::class, function (Faker $faker) {
    $gb = $faker->numerify();
    $gb2 = $faker->numerify();
    return [
        'title' => $faker->numerify('Plan ##'),
        'currency' => 'usd',
        'price' => $faker->randomNumber(2),
        'description' => '',
        'stripe_id' => 1,
        'interval' => 'month',
        'storage' => $gb,
        'video_storage' => $gb2,
        'name' => $faker->word,
        'quantity' => random_int(1, 5),
    ];
});
