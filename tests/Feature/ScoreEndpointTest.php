<?php

namespace Tests\Feature;

use KIPR\Team;
use KIPR\User;
use KIPR\Match;
use KIPR\Ruleset;
use Carbon\Carbon;
use Tests\TestCase;
use KIPR\Competition;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScoreEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function testInvalidRequest()
    {
        // Setup the ruleset in the db
        $ruleset = new Ruleset;
        $ruleset->events = json_decode('{"event_1":{"min": 0, "max": 5}, "event_2":{"min": 0, "max": 5}}');
        $ruleset->rules=[];
        $ruleset->save();

        // Get a Competition
        $competition = factory(Competition::class)->create();
        $competition->setRuleset($ruleset)->save();

        // Get Two Teams
        $teamA = factory(Team::class)->create();
        $teamB = factory(Team::class)->create();

        // Setup a Match
        $match = Match::create([
          'match_time' => Carbon::Now(),
          'match_type' => 'testing',
          'round' => 0,
          'competition_id' => $competition->id,
          'team_A' => $teamA->id,
          'team_B' => $teamB->id
        ]);
        // Setup Authentication
        Passport::actingAs(factory(User::class)->create(), ['judging']);

        // Setup invalid results
        $results = '{"A":{"event_3": 1, "event_2": 2}, "B": {"5": 2, "event_2": 1}}';
        // Run test
        $url = '/api/competition/' . $competition->id . '/match/' . $match->id . '/score';
        $response = $this->json('POST', $url, [
          'results' => $results
        ]);
        $response->assertStatus(400);
    }

    public function testValidRequest()
    {
        // Setup the ruleset in the db
        $ruleset = new Ruleset;
        $ruleset->events = json_decode('{"event_1":{"min": 0, "max": 5}, "event_2":{"min": 0, "max": 5}}');
        $ruleset->rules=[];
        $ruleset->save();

        // Get a Competition
        $competition = factory(Competition::class)->create();
        $competition->setRuleset($ruleset)->save();

        // Get Two Teams
        $teamA = factory(Team::class)->create();
        $teamB = factory(Team::class)->create();

        // Setup a Match
        $match = Match::create([
          'match_time' => Carbon::Now(),
          'match_type' => 'testing',
          'round' => 0,
          'competition_id' => $competition->id,
          'team_A' => $teamA->id,
          'team_B' => $teamB->id
        ]);

        // Setup Authentication
        Passport::actingAs(factory(User::class)->create(), ['judging']);

        // Setup results
        $results = '{"A":{"event_1": 1, "event_2": 2}, "B": {"event_1": 2, "event_2": 1}}';
        // Run test
        $url = '/api/competition/' . $competition->id . '/match/' . $match->id . '/score';
        $response = $this->json('POST', $url, [
          'results' => $results
        ]);

        $expected = json_decode('{"A": {"event_1": 1, "event_2": 2, "total": 3}, "B": {"event_1": 2, "event_2": 1, "total": 3}}', true);

        $response->assertStatus(200);
        $response->assertJson([
          'status' => 'success',
          'message' => 'match scored',
          'results' => $expected
        ]);
    }

    public function test_unauthenticated_users_cannot_submit_match_results() {
      // Setup the ruleset in the db
      $ruleset = new Ruleset;
      $ruleset->events = json_decode('{"event_1":{"min": 0, "max": 5}, "event_2":{"min": 0, "max": 5}}');
      $ruleset->rules=[];
      $ruleset->save();

      // Get a Competition
      $competition = factory(Competition::class)->create();
      $competition->setRuleset($ruleset)->save();

      // Get Two Teams
      $teamA = factory(Team::class)->create();
      $teamB = factory(Team::class)->create();

      // Setup a Match
      $match = Match::create([
        'match_time' => Carbon::Now(),
        'match_type' => 'testing',
        'round' => 0,
        'competition_id' => $competition->id,
        'team_A' => $teamA->id,
        'team_B' => $teamB->id
      ]);

      // Setup results
      $results = '{"A":{"event_1": 1, "event_2": 2}, "B": {"event_1": 2, "event_2": 1}}';
      // Run test
      $url = '/api/competition/' . $competition->id . '/match/' . $match->id . '/score';
      $response = $this->json('POST', $url, [
        'results' => $results
      ]);

      $expected = json_decode('{"A": {"event_1": 1, "event_2": 2, "total": 3}, "B": {"event_1": 2, "event_2": 1, "total": 3}}', true);

      $response->assertStatus(401);
    }
}
