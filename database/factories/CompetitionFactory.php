<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(KIPR\Competition::class, function (Faker $faker) {
    return [
      'name' => $faker->city . " Regional",
      'location' => $faker->address . " " . $faker->city . ", " . $faker->state . " " . $faker->postcode,
      'start_date' => $faker->dateTime,
      'end_date' => $faker->dateTime,
      'ruleset_id' => 0
    ];
});
