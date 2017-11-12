<?php

namespace Tests\Unit;

use KIPR\Judging\Tabulator;
use Tests\TestCase;
use KIPR\Competition;
use KIPR\Exceptions\InvalidResultException;

class ScoringTest extends TestCase
{

    public function test_no_modifiers()
    {
        $rules='{
            "events": {
                "robot_on_terrace": {"min": 0, "max": 2},
                "red_ball_in_cup": {"min": 0, "max": 10},
                "blue_ball_in_cup": {"min": 0, "max": 10}
            },
            "rules": []
        }';
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
        
        $score = Tabulator::score(json_decode($rules), json_decode($results, true));
        $this->assertEquals($score, json_decode($expected, true));
    }

    public function test_no_points()
    {
        $rules='{
            "events": {
                "robot_on_terrace": {"min": 0, "max": 2},
                "red_ball_in_cup": {"min": 0, "max": 10},
                "blue_ball_in_cup": {"min": 0, "max": 10}
            },
            "rules": []
        }';
        $results = '{
        }';
        $expected = '{
            "total": 0
        }';
        
        $score = Tabulator::score(json_decode($rules), json_decode($results, true));
        $this->assertEquals($score, json_decode($expected, true));
    }

    public function test_fail_to_score_invalid()
    {
        $rules='{
            "events": {
                "robot_on_terrace": {"min": 0, "max": 2},
                "red_ball_in_cup": {"min": 0, "max": 10},
                "blue_ball_in_cup": {"min": 0, "max": 10}
            },
            "rules": []
        }';
        $results = '{
            "robot_on_terrace": 1,
            "red_ball_in_cup": 5,
            "green_ball_in_cup": 5
        }';
        
        $this->expectException(\KIPR\Exceptions\InvalidResultException::class);
        $score = Tabulator::score(json_decode($rules), json_decode($results, true));
    }

    public function test_multiplier()
    {
        $rules='{
            "events": {
                "event": {"min": 0, "max": 10}
            },
            "rules": [
                {
                    "type": "multiplier",
                    "target": "event",
                    "value": 3
                }
            ]
        }';

        $results = '{"event": 4}';
        $expected = '{"event": 12, "total": 12}';
        $score = Tabulator::score(json_decode($rules), json_decode($results, true));
        $this->assertEquals($score, json_decode($expected, true));
    }

    public function test_bonus()
    {
        $rules='{
            "events": {
                "event": {"min": 0, "max": 10}
            },
            "rules": [
                {
                    "type": "fixed",
                    "target": "event",
                    "value": 5
                }
            ]
        }';

        $results = '{"event": 4}';
        $expected = '{"event": 9, "total": 9}';
        $score = Tabulator::score(json_decode($rules), json_decode($results, true));
        $this->assertEquals($score, json_decode($expected, true));
    }

    public function test_conditional()
    {
        $rules='{
            "events": {
                "event": {"min": 0, "max": 10}
            },
            "rules": [
                {
                    "type": "conditional",
                    "value": "event == 3",
                    "target": [{"type": "multiplier", "value": 10, "target": "event"}]
                }
            ]
        }';

        $results = '{"event": 4}';
        $expected = '{"event": 4, "total": 4}';
        $score = Tabulator::score(json_decode($rules), json_decode($results, true));
        $this->assertEquals($score, json_decode($expected, true));

        $results = '{"event": 3}';
        $expected = '{"event": 30, "total": 30}';
        $score = Tabulator::score(json_decode($rules), json_decode($results, true));
        $this->assertEquals($score, json_decode($expected, true));
    }

    public function test_scoring_tabulation()
    {
        $rules='{
            "events": {
                "robot_on_terrace": {"min": 0, "max": 2},
                "red_ball_in_cup": {"min": 0, "max": 10},
                "blue_ball_in_cup": {"min": 0, "max": 10}
            },
            "rules": [
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
            ]
        }';
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

        $score = Tabulator::score(json_decode($rules), json_decode($results, true));
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

        $score = Tabulator::score(json_decode($rules), json_decode($results, true));
        $this->assertEquals(json_decode($expected, true), $score);
    }
}

