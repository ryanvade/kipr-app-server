<?php

namespace Tests\Unit;

use KIPR\Team;
use KIPR\User;
use Carbon\Carbon;
use Tests\TestCase;
use KIPR\Competition;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class TeamSigninTest extends TestCase
{
    use RefreshDatabase;

    public function test_teams_that_are_registered_can_sign_in()
    {
        # given a competition
        $competition = factory(Competition::class)->create();
        # and a logged in user
        Passport::actingAs(
          factory(User::class)->create(),
          ['sign_in']
        );
        # and a team
        $team = factory(Team::class)->create();
        # which is registered with the competition
        $competition->teams()->attach($team);
        # signin the team
        $response = $this->json('POST', "/api/competition/". $competition->id ."/team/" . $team->id . "/signin");
        # check if the response
        $response->assertStatus(200)
                 ->assertJson([
                   'team_id' => $team->id,
                   'competition_id' => $competition->id,
                 ]);
        $this->assertDatabaseHas('competition_teams', [
          'competition_id' => $competition->id,
          'team_id' => $team->id,
          'signed_in' => true
        ]);
    }

    public function test_teams_that_are_not_registered_cannot_sign_in()
    {
        # given a competition
        $competition = factory(Competition::class)->create();
        # and a logged in user
        Passport::actingAs(
          factory(User::class)->create(),
          ['sign_in']
        );
        # and a team
        $team = factory(Team::class)->create();
        # signin the team
        $response = $this->json('POST', "/api/competition/" . $competition->id . "/team/" . $team->id . "/signin");
        # check the response
        $response->assertStatus(409)
               ->assertJson([
                 'status' => 'error',
                 'message' => 'the team is not registered with the competition'
               ]);
        $this->assertDatabaseMissing('competition_teams', [
                 'competition_id' => $competition->id,
                 'team_id' => $team->id,
                 'signed_in' => true
               ]);
    }

    public function test_authentication_is_required_to_sign_in_teams()
    {
        # given a competition
        $competition = factory(Competition::class)->create();
        # and a team
        $team = factory(Team::class)->create();
        # register the team with the competition
        $competition->teams()->attach($team);
        # signin the team
        $response = $this->json('POST', "/api/competition/1/team/1/signin");
        # check the response
        $response->assertStatus(401);
    }
}
