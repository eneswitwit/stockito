<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Brand::class, function (Faker $faker) {
    return [
        'brand_name' => $faker->company,
        'company_name' => $faker->company,
        'address_1' => $faker->address,
        'address_2' => $faker->address,
        'city' => $faker->city,
        'zip' => $faker->postcode,
        'eur_uid' => $faker->randomNumber(5),
        'homepage' => $faker->url,
        'phone' => $faker->phoneNumber,
        'contact_first_name' => $faker->firstName,
        'contact_last_name' => $faker->lastName,
        'contact_title' => $faker->name
    ];
});
