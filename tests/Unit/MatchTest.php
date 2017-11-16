<?php

namespace Tests\Unit;

use KIPR\Team;
use KIPR\Match;
use Carbon\Carbon;
use Tests\TestCase;
use KIPR\Competition;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MatchTest extends TestCase
{
    # https://laravel.com/docs/master/database-testing#resetting-the-database-after-each-test
    use RefreshDatabase;

    public function test_it_has_a_competition()
    {
        # given a match
        $match = factory(Match::class)->create();
        # and the only competition in the DB
        $competition = Competition::first();
        # check if they are the same
        $this->assertEquals($match->competition, $competition);
    }

    public function test_it_has_team_A_but_team_B_can_be_null()
    {
        # given a team
        $team = factory(Team::class)->create();
        # and a competition
        $competition = factory(Competition::class)->create();
        # create a match
        $competition->matches()->create([
        'match_time' => Carbon::Now(),
        'team_A' => $team->id
      ]);
        # get the match
        $match = $competition->matches()->first();
        # refresh the team
        $team = $team->fresh();
        # check if the match's team A is the same as the created team
        $this->assertEquals($match->teamA, $team);
        # check if the match's team B is null
        $this->assertNull($match->teamB);
    }

    public function test_it_can_have_two_team()
    {
        # given team A
        $teamA = factory(Team::class)->create();
        # and team B
        $teamB = factory(Team::class)->create();
        # and a competition
        $competition = factory(Competition::class)->create();
        # create a match
        $competition->matches()->create([
        'match_time' => Carbon::Now(),
        'team_A' => $teamA->id,
        'team_B' => $teamB->id,
      ]);
        # get the match
        $match = $competition->matches()->first();
        # refresh the teams
        $teamA = $teamA->fresh();
        $teamB = $teamB->fresh();
        # check if the match's team A is the same as the created team
        $this->assertEquals($match->teamA, $teamA);
        # check if the match's team B is the same as the created team
        $this->assertEquals($match->teamB, $teamB);
    }
}
