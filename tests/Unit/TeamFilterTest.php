<?php

namespace Tests\Unit;

use KIPR\Team;
use Tests\TestCase;
use KIPR\Competition;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamFilterTest extends TestCase
{
  use RefreshDatabase;

  public function test_getting_all_non_registered_teams_does_not_include_registered_teams() {
    // given a competition
    $competition = factory(Competition::class)->create();
    // and a couple of teams
    $registered_team = factory(Team::class)->create();
    $non_registered_team = factory(Team::class)->create();
    // register one of the teams with the competition
    $competition->teams()->attach($registered_team);
    // now get the teams not registered with the competition
    $response = $this->get('/api/team?registered=1,0'); // 1 = competition id, 0 = false
    // now check the response JSON
    $response->assertStatus(200)
             ->assertJsonMissingExact($registered_team->toArray())
             ->assertJson([
               'total' => '1'
             ]);
  }

  public function test_getting_all_registered_teams_does_not_include_nonregistered_teams() {
    // given a competition
    $competition = factory(Competition::class)->create();
    // and a couple of teams
    $registered_team = factory(Team::class)->create();
    $non_registered_team = factory(Team::class)->create();
    // register one of the teams with the competition
    $competition->teams()->attach($registered_team);
    // now get the teams not registered with the competition
    $response = $this->get('/api/team?registered=1,1'); // 1 = competition id, 0 = false
    // now check the response JSON
    $response->assertStatus(200)
             ->assertJsonMissingExact($non_registered_team->toArray())
             ->assertJson([
               'total' => '1'
             ]);
  }
}
