<?php

namespace Tests\Unit;

use KIPR\Team;
use Tests\TestCase;
use KIPR\Competition;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
    $response = $this->get('/api/competition?registered=1,0'); // 1 = competition id, 0 = false
    // now check the response JSON
    $response->assertStatus(200)
             ->assertJsonMissingExact($registered_competition->toArray())
             ->assertJson([
               'total' => '1'
             ]);
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
    $response = $this->get('/api/competition?registered=1,1'); // 1 = competition id, 0 = false
    // now check the response JSON
    $response->assertStatus(200)
             ->assertJsonMissingExact($non_registered_competition->toArray())
             ->assertJson([
               'total' => '1'
             ]);
  }
}
