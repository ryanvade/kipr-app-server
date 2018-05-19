<?php

namespace Tests\Unit;

use KIPR\Judging\Tabulator;
use Tests\TestCase;
use KIPR\Competition;
use KIPR\Ruleset;
use KIPR\Exceptions\InvalidResultException;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ScoringTest extends TestCase
{
    use RefreshDatabase;
    public function test_no_modifiers()
    {
        $ruleset = new Ruleset;
        $ruleset->events = json_decode('{
                "robot_on_terrace": {"min": 0, "max": 2},
                "red_ball_in_cup": {"min": 0, "max": 10},
                "blue_ball_in_cup": {"min": 0, "max": 10}
            }');
        $ruleset->rules = [];

        $results = '{
            "robot_on_terrace": 1,
            "red_ball_in_cup": 5,
            "blue_ball_in_cup": 5
        }';
        $expected = '{
            "robot_on_terrace": 1,
            "red_ball_in_cup": 5,
            "blue_ball_in_cup": 5,
            "total": 11
        }';

        $score = Tabulator::score($ruleset, json_decode($results, true));
        $this->assertEquals($score, json_decode($expected, true));
    }

    public function test_no_points()
    {
        $ruleset = new Ruleset;
        $ruleset->events = json_decode('{
                "robot_on_terrace": {"min": 0, "max": 2},
                "red_ball_in_cup": {"min": 0, "max": 10},
                "blue_ball_in_cup": {"min": 0, "max": 10}
            }');
        $ruleset->rules = [];

        $results = '{
        }';
        $expected = '{
            "total": 0
        }';

        $score = Tabulator::score($ruleset, json_decode($results, true));
        $this->assertEquals($score, json_decode($expected, true));
    }

    public function test_fail_to_score_invalid()
    {
        $ruleset = new Ruleset;
        $ruleset->events = json_decode('{
                "robot_on_terrace": {"min": 0, "max": 2},
                "red_ball_in_cup": {"min": 0, "max": 10},
                "blue_ball_in_cup": {"min": 0, "max": 10}
            }');
        $ruleset->rules = [];

        $results = '{
            "robot_on_terrace": 1,
            "red_ball_in_cup": 5,
            "green_ball_in_cup": 5
        }';

        $this->expectException(\KIPR\Exceptions\InvalidResultException::class);
        $score = Tabulator::score($ruleset, json_decode($results, true));
    }

    public function test_multiplier()
    {
        $ruleset = new Ruleset;
        $ruleset->events = json_decode('{
                "event": {"min": 0, "max": 10}
            }');
        $ruleset->rules = json_decode('[
                {
                    "type": "multiplier",
                    "target": "event",
                    "value": 3
                }
            ]');

        $results = '{"event": 4}';
        $expected = '{"event": 12, "total": 12}';
        $score = Tabulator::score($ruleset, json_decode($results, true));
        $this->assertEquals($score, json_decode($expected, true));
    }

    public function test_bonus()
    {
        $ruleset = new Ruleset;
        $ruleset->events = json_decode('{
                "event": {"min": 0, "max": 10}
            }');
        $ruleset->rules = json_decode('
            [
                {
                    "type": "fixed",
                    "target": "event",
                    "value": 5
                }
            ]');

        $results = '{"event": 4}';
        $expected = '{"event": 9, "total": 9}';
        $score = Tabulator::score($ruleset, json_decode($results, true));
        $this->assertEquals($score, json_decode($expected, true));
    }

    public function test_conditional()
    {
        $ruleset = new Ruleset;
        $ruleset->events = json_decode('{
                "event": {"min": 0, "max": 10}
            }');
        $ruleset->rules = json_decode('[
                {
                    "type": "conditional",
                    "value": "event == 3",
                    "target": [{"type": "multiplier", "value": 10, "target": "event"}]
                }
            ]');

        $results = '{"event": 4}';
        $expected = '{"event": 4, "total": 4}';
        $score = Tabulator::score($ruleset, json_decode($results, true));
        $this->assertEquals($score, json_decode($expected, true));

        $results = '{"event": 3}';
        $expected = '{"event": 30, "total": 30}';
        $score = Tabulator::score($ruleset, json_decode($results, true));
        $this->assertEquals($score, json_decode($expected, true));
    }

    public function test_scoring_tabulation()
    {
        $ruleset = new Ruleset;
        $ruleset->events = json_decode('{
                "robot_on_terrace": {"min": 0, "max": 2},
                "red_ball_in_cup": {"min": 0, "max": 10},
                "blue_ball_in_cup": {"min": 0, "max": 10}
            }');
        $ruleset->rules = json_decode(
            '[
                {
                    "type": "multiplier",
                    "target": "robot_on_terrace",
                    "value": 15
                },
                {
                    "type": "conditional",
                    "value": "blue_ball_in_cup == red_ball_in_cup",
                    "target": [{
                        "type": "fixed",
                        "target": "balls_matched",
                        "value": 20
                    }]
                },
                {
                    "type": "conditional",
                    "value": "robot_on_terrace == 30",
                    "target": [{
                        "type": "multiplier",
                        "target": "robot_on_terrace",
                        "value": 2
                    }]
                }
            ]');

        $results = '{
            "robot_on_terrace": 1,
            "red_ball_in_cup": 5,
            "blue_ball_in_cup": 5
        }';

        $expected = '{
            "robot_on_terrace": 15,
            "red_ball_in_cup": 5,
            "blue_ball_in_cup": 5,
            "balls_matched": 20,
            "total": 45
        }';

        $score = Tabulator::score($ruleset, json_decode($results, true));
        $this->assertEquals(json_decode($expected, true), $score);

        $results = '{
            "robot_on_terrace": 2,
            "red_ball_in_cup": 7,
            "blue_ball_in_cup": 3
        }';

        $expected = '{
            "robot_on_terrace": 60,
            "red_ball_in_cup": 7,
            "blue_ball_in_cup": 3,
            "total": 70
        }';

        $score = Tabulator::score($ruleset, json_decode($results, true));
        $this->assertEquals(json_decode($expected, true), $score);
    }
}
