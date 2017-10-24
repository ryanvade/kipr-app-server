<?php

use Faker\Generator as Faker;

$factory->define(KIPR\Team::class, function (Faker $faker) {
    return [
        'name' => $faker->company
    ];
});
