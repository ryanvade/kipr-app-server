<?php

namespace Tests\Feature;

use KIPR\Ruleset;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScoreEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function testInvalidRequest()
    {
        $response = $this->get('/match/score');
        $response->assertStatus(400);
    }

    public function testValidRequest()
    {
        // Setup the ruleset in the db
        $ruleset = new Ruleset;
        $ruleset->events = json_decode('{"event_1":{"min": 0, "max": 5}, "event_2":{"min": 0, "max": 5}}');
        $ruleset->rules=[];
        $ruleset->save();

        // Run test
        $response = $this->get('/match/score?ruleset_id=1&results={"A":{"event_1": 1, "event_2": 2}, "B": {"event_1": 2, "event_2": 1}}');
        $expected = '{"A": {"event_1": 1, "event_2": 2, "total": 3}, "B": {"event_1": 2, "event_2": 1, "total": 3}}';

        $response->assertStatus(200);
        $response->assertJson(json_decode($expected, true));
    }
}
