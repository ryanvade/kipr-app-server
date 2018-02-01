<?php

namespace Tests\Unit;

use KIPR\Team;
use KIPR\Match;
use Carbon\Carbon;
use Tests\TestCase;
use KIPR\Competition;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MatchCreationTest extends TestCase
{
    # https://laravel.com/docs/master/database-testing#resetting-the-database-after-each-test
    use RefreshDatabase;

    public function test_seeding_match_correct()
    {
        $TEAMS = 22;
        $competition = factory(Competition::class)->create();
        $teamFactory = factory(Team::class);

        for($i = 0; $i < $TEAMS; $i++) {
            $competition->teams()->attach($teamFactory->create());
        }
        $this->assertEquals($TEAMS, count($competition->teams()->get()));

        $competition->generateMatches();
        $competition->scheduleMatches();

        $this->assertEquals($TEAMS*3, count($competition->matches()->where('match_type', '=', 'seeding')->get()));
        $this->assertEquals(16, count($competition->matches()->where([['match_type', '=', 'double_elim_win'], ['round', '=', '0']])->get()));
        $this->assertEquals(8 , count($competition->matches()->where([['match_type', '=', 'double_elim_win'], ['round', '=', '1']])->get()));
        $this->assertEquals(4 , count($competition->matches()->where([['match_type', '=', 'double_elim_win'], ['round', '=', '2']])->get()));
        $this->assertEquals(2 , count($competition->matches()->where([['match_type', '=', 'double_elim_win'], ['round', '=', '3']])->get()));
        $this->assertEquals(1 , count($competition->matches()->where([['match_type', '=', 'double_elim_win'], ['round', '=', '4']])->get()));
        $this->assertEquals(8 , count($competition->matches()->where([['match_type', '=', 'double_elim_lose'], ['round', '=', '1']])->get()));
        $this->assertEquals(4 , count($competition->matches()->where([['match_type', '=', 'double_elim_lose'], ['round', '=', '2']])->get()));
        $this->assertEquals(2 , count($competition->matches()->where([['match_type', '=', 'double_elim_lose'], ['round', '=', '3']])->get()));
        $this->assertEquals(1 , count($competition->matches()->where([['match_type', '=', 'double_elim_lose'], ['round', '=', '4']])->get()));
        $this->assertEquals(1 , count($competition->matches()->where('match_type', '=', 'double_elim_finals')->get()));
    }
}
