<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(KIPR\Competition::class, function (Faker $faker) {
  $start = $faker->dateTime;
  $end = Carbon::instance($start)->addDays(2);
    return [
      'name' => $faker->city . " Regional",
      'location' => $faker->address . " " . $faker->city . ", " . $faker->state . " " . $faker->postcode,
      'start_date' => $start,
      'end_date' => $end,
    ];
});
