<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Voucher::class, function (Faker $faker) {
	return [
		'code' => $faker->unique()->randomNumber(5),
		'sale' => $faker->randomElement($array = array ('10','20','30','50'))
	];
});
