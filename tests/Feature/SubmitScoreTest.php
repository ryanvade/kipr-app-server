<?php

namespace Tests\Feature;

use KIPR\Match;
use Tests\TestCase;
use KIPR\Competition;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubmitScoreTest extends TestCase
{
    use RefreshDatabase;
    public function test_a_judge_can_submit_match_scores()
    {
        # Given a competition
        $competition = Factory(Competition::class)->create();
        # and a rule
        $competition->rule()->create([
          'year' => '2017',
          'rules' => json_encode([
                [
                  "type" =>  "count",
                  "object" =>  "robot",
                  "location" =>  "terrace",
                  "count" =>  1,
                  "value" =>  15,
                ],
                [
                  "type" =>  "count",
                  "object" =>  "golfball",
                  "location" =>  "cup",
                  "count" =>  1,
                  "value" =>  1,
                ],
                [
                  "type" =>  "multiplier",
                  "object" =>  "golfball",
                  "location" =>  "cup",
                  "count" =>  3,
                  "value" =>  5,
                ],
          ])
        ]);
        # and a match
        $match = Factory(Match::class)->create();
        # and a judging code
        $code = '';
        # and a set of match results
        $results = '';
        # post the results to the server
        $response = $this->json('patch', '/api/match/' . $match->id . '/score', [
        'results' => json_encode($results)
      ]);

        $response
                ->assertStatus(200)
                ->assertJson([
                  'status' => 'scored'
                ]);
    }
}
