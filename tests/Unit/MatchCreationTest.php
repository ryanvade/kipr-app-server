<?php

namespace Tests\Unit;

use KIPR\Team;
use KIPR\Match;
use Carbon\Carbon;
use Tests\TestCase;
use KIPR\Competition;
use KIPR\Http\Controllers\ScheduleController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


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
            $competition->teams()->attach($teamFactory->create(), ["signed_in" => true]);
        }
        $this->assertEquals($TEAMS, count($competition->teams()->get()));
        $this->assertEquals($TEAMS, count($competition->teams()->withPivot("signed_in")->where("signed_in", true)->get()));

        $controller = new ScheduleController();
        $schedule = $controller->schedule($competition);

        $this->assertEquals($TEAMS*3, count($schedule["seeding"]));
        // dd(Match::all());
        $this->assertEquals(16, count($schedule["elimination"]->where('match_type', 'WW')->where('round', '0')));
        $this->assertEquals(8 , count($schedule["elimination"]->where('match_type', 'double_elim_win')->where('round', '1')));
        $this->assertEquals(4 , count($schedule["elimination"]->where('match_type', 'double_elim_win')->where('round', '2')));
        $this->assertEquals(2 , count($schedule["elimination"]->where('match_type', 'double_elim_win')->where('round', '3')));
        $this->assertEquals(1 , count($schedule["elimination"]->where('match_type', 'double_elim_win')->where('round', '4')));
        $this->assertEquals(8 , count($schedule["elimination"]->where('match_type', 'double_elim_lose')->where('round', '1')));
        $this->assertEquals(4 , count($schedule["elimination"]->where('match_type', 'double_elim_lose')->where('round', '2')));
        $this->assertEquals(2 , count($schedule["elimination"]->where('match_type', 'double_elim_lose')->where('round', '3')));
        $this->assertEquals(1 , count($schedule["elimination"]->where('match_type', 'double_elim_lose')->where('round', '4')));
        $this->assertEquals(1 , count($schedule["elimination"]->where('match_type', 'double_elim_finals')));
    }
}
