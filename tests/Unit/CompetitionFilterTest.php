<?php

namespace Tests\Unit;

use KIPR\Team;
use Tests\TestCase;
use KIPR\Competition;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class CompetitionFilterTest extends TestCase
{
  use RefreshDatabase;

  public function test_getting_all_non_registered_competitions_does_not_include_registered_competitions() {
    // given a couple of competition
    $registered_competition = factory(Competition::class)->create();
    $non_registered_competition = factory(Competition::class)->create();
    // and a teams
    $team = factory(Team::class)->create();
    // register one of the teams with the competition
    $registered_competition->teams()->attach($team);
    // now get the teams not registered with the competition
    $response = $this->get('/api/competition?registered=' . $team->id . ',0'); // 1 = team id, 0 = false
    // now check the response JSON
    $response->assertStatus(200)
             ->assertJsonMissingExact($registered_competition->toArray())
             ->assertSeeText('"total":1');
  }

  public function test_getting_all_registered_competitionss_does_not_include_nonregistered_competitions() {
    // given a couple of competition
    $registered_competition = factory(Competition::class)->create();
    $non_registered_competition = factory(Competition::class)->create();
    // and a teams
    $team = factory(Team::class)->create();
    // register one of the teams with the competition
    $registered_competition->teams()->attach($team);
    // now get the teams not registered with the competition
    $response = $this->get('/api/competition?registered='. $team->id . ',1'); // 1 = team id, 1 = true
    // now check the response JSON
    $response->assertStatus(200)
             ->assertJsonMissingExact($non_registered_competition->toArray())
             ->assertSeeText('"total":1');
  }
}
