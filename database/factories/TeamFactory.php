<?php

use Faker\Generator as Faker;

$factory->define(KIPR\Team::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'email' => $faker->email,
        'code' => $faker->numberBetween(0, 999999)
    ];
});
