<?php

namespace Tests\Unit;

use KIPR\Team;
use Carbon\Carbon;
use Tests\TestCase;
use KIPR\Competition;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamSigninTest extends TestCase
{
    use RefreshDatabase;

    public function test_teams_that_are_registered_can_sign_in()
    {
        # given a competition
        $competition = factory(Competition::class)->create();
        # and a team
        $team = factory(Team::class)->create();
        # which is registered with the competition
        $competition->teams()->attach($team);
        # signin the team
        $response = $this->json('POST', "/api/competition/1/team/1/signin");
        # check if the response
        $response->assertStatus(200)
                 ->assertJson([
                   'team_id' => $team->id,
                   'competition_id' => $competition->id,
                 ]);
    }

    public function test_teams_that_are_not_registered_cannot_sign_in()
    {
        # given a competition
        $competition = factory(Competition::class)->create();
        # and a team
        $team = factory(Team::class)->create();
        # attempt to sign in
        $response = $this->json('POST', "/api/competition/1/team/1/signin");
        # check the response
        $response->assertStatus(409)
               ->assertJson([
                 'status' => 'error',
                 'message' => 'the team is not registered with the competition'
               ]);
    }
}
