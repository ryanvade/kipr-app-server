<?php

namespace Tests\Feature;

use KIPR\Team;
use Carbon\Carbon;
use Tests\TestCase;
use KIPR\Competition;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamRegistrationTest extends TestCase
{
  use RefreshDatabase;
    public function test_anyone_can_get_teams_registered_with_a_competition() {
      // given a competition
      $competition = factory(Competition::class)->create();
      // and a team
      $team = factory(Team::class)->create();
      // that is registered with the competition
      $competition->teams()->attach($team);
      // get all of the competitions registered with the competition
      $response = $this->json('GET', '/api/competition/1/team');
      // and check that the response has the team
      $response->assertStatus(200)
               ->assertJson([
                   $team->toArray()
               ]);
    }

    public function test_getting_the_list_of_teams_registered_with_a_competition_does_not_include_nonregistered_teams() {
      // given a competition
      $competition = factory(Competition::class)->create();
      // and a Team
      $registeredTeam = factory(Team::class)->create();
      // and another team
      $nonRegisteredTeam = factory(Team::class)->create();
      // register the first Team
      $competition->teams()->attach($registeredTeam);
      // then get the list of registered teams
      $response = $this->json('GET', '/api/competition/1/team');
      // and check that the second team is not included
      $response->assertStatus(200)
                ->assertJson([
                    $registeredTeam->toArray()
                ])
                ->assertJsonMissing([
                    $nonRegisteredTeam->toArray()
                ]);
    }

    public function test_getting_the_list_of_registered_teams_can_be_filtered_to_only_unsigned_in_teams() {
      // given a competition
      $competition = factory(Competition::class)->create();
      // and a Team
      $signedInTeam = factory(Team::class)->create();
      // and another team
      $nonSignedInTeam = factory(Team::class)->create();
      // register both teams
      $competition->teams()->attach($signedInTeam);
      $competition->teams()->attach($nonSignedInTeam);
      // Sign In the first team
      $teamPivot = $competition->teams()->where('team_id', $signedInTeam->id)->first();
      $teamPivot->pivot->signed_in = true;
      $teamPivot->pivot->sign_in_time = Carbon::now();
      $teamPivot->pivot->save();
      // then get the list of registered teams
      $response = $this->json('GET', '/api/competition/1/team?signed_in=0');
      // dd($response);
      // and check that the second team is not included
      $response->assertStatus(200)
                ->assertJsonMissingExact(
                    $nonSignedInTeam->fresh()->toArray()
                );
    }

    public function test_getting_the_list_of_registered_teams_can_be_filtered_to_only_signed_in_teams()
    {
      // given a competition
      $competition = factory(Competition::class)->create();
      // and a Team
      $signedInTeam = factory(Team::class)->create();
      // and another team
      $nonSignedInTeam = factory(Team::class)->create();
      // register both teams
      $competition->teams()->attach($signedInTeam);
      $competition->teams()->attach($nonSignedInTeam);
      // Sign In the first team
      $teamPivot = $competition->teams()->where('team_id', $signedInTeam->id)->first();
      $teamPivot->pivot->signed_in = true;
      $teamPivot->pivot->sign_in_time = Carbon::now();
      $teamPivot->pivot->save();
      // then get the list of registered teams
      $response = $this->json('GET', '/api/competition/1/team?signed_in=1');
      // dd($response);
      // and check that the second team is not included
      $response->assertStatus(200)
                ->assertJsonMissingExact(
                    $signedInTeam->fresh()->toArray()
                );
    }
}
