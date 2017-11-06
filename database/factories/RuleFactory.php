<?php

use KIPR\Rule;
use KIPR\Competition;
use Faker\Generator as Faker;

$factory->define(Rule::class, function (Faker $faker) {
    if (($competition = Competition::first()) == null) {
        $competition = factory(Competition::class)->create();
    }
    return [
        'competition_id' => $competition->id,
        'year' => $faker->year,
        'rules' => json_encode([
              [
                "type" =>  "count",
                "object" =>  "robot",
                "location" =>  "terrace",
                "count" =>  1,
                "value" =>  15,
              ],
              [
                "type" =>  "count",
                "object" =>  "golfball",
                "location" =>  "cup",
                "count" =>  1,
                "value" =>  1,
              ],
              [
                "type" =>  "multiplier",
                "object" =>  "golfball",
                "location" =>  "cup",
                "count" =>  3,
                "value" =>  5,
              ],
        ])
    ];
});
