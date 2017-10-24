<?php

use KIPR\Competition;
use Faker\Generator as Faker;

$factory->define(KIPR\Match::class, function (Faker $faker) {
    if (($competition = Competition::first()) == null) {
        $competition = factory(Competition::class)->create();
    }
    return [
        'match_time' => $faker->dateTime,
        'competition_id' => $competition->id
    ];
});
