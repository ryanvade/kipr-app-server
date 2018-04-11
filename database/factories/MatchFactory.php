<?php

use KIPR\Team;
use KIPR\Competition;
use Faker\Generator as Faker;

$factory->define(KIPR\Match::class, function (Faker $faker) {
    if (($competition = Competition::first()) == null) {
        $competition = factory(Competition::class)->create();
    }

    if (($team_A = Team::find(1)) == null) {
        $team_A = factory(Team::class)->create();
    }

    if (($team_B = Team::find(2)) == null) {
        $team_B = factory(Team::class)->create();
    }
    return [
        'match_time' => $faker->dateTime,
        'round' => $faker->numberBetween(0, 10),
        'match_type' => "test", #$faker->randomElement(['seeding', 'double_elim']),
        'competition_id' => $competition->id,
        'team_A' => $team_A->id,
        'team_B' => $team_B->id
    ];
});
