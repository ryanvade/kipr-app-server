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
        # get the match
        $competition = factory(Competition::class)->create();
        $teamFactory = factory(Team::class);

        for($i = 0; $i < 10; $i++) {
            $competition->teams()->attach($teamFactory->create());
        }
        $this->assertEquals(10, count($competition->teams()->get()));

        $competition->generateMatches();

        $matches = $competition->matches()->get();
        $this->assertEquals(3*count($competition->teams()->get()), count($matches));
    }
}
