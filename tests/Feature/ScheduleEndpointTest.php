<?php

namespace Tests\Feature;

use KIPR\Team;
use KIPR\Match;
use Carbon\Carbon;
use KIPR\Competition;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScheduleEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function testGetAndCommit()
    {
        $TEAMS = 4;
        $competition = factory(Competition::class)->create();
        $teamFactory = factory(Team::class);

        for($i = 0; $i < $TEAMS; $i++) {
            $competition->teams()->attach($teamFactory->create(), ["signed_in" => true]);
        }
        $this->assertEquals($TEAMS, count($competition->teams()->get()));
        $this->assertEquals($TEAMS, count($competition->teams()->withPivot("signed_in")->where("signed_in", true)->get()));

        $schedule = $this->get("/api/competition/$competition->id/schedule");

        $schedule->assertStatus(200);

        // Should not have changed db
        $this->assertEquals(0, count($competition->matches()->get()));

        $commit = $this->post("/api/competition/$competition->id/updateSchedule");
        $commit->assertStatus(200);
        $this->assertNotEquals(0, count($competition->matches()->get()));
    }
}
