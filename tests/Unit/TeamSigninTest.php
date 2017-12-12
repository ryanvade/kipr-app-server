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
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_teams_can_sign_in()
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
}
