<?php

namespace Tests\Unit;

use KIPR\Judging\Tabulator;
use Tests\TestCase;
use KIPR\Competition;

class ScoringTest extends TestCase
{
    public function test_scoring_tabulation()
    {
        $rules='{
            "events": {
                "robot_on_terrace": {"min": 0, "max": 2},
                "red_ball_in_cup": {"min": 0, "max": 10},
                "blue_ball_in_cup": {"min": 0, "max": 10}
            },
            "rules": [{
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
                    "value": "robot_on_terrace == 2",
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

        $score = Tabulator::score($rules, $results);
        
        $expected = json_decode('{
            "robot_on_terrace": 15,
            "red_ball_in_cup": 5,
            "blue_ball_in_cup": 5,
            "balls_matched": 20,
            "total": 45
        }', true);

        $this->assertEquals($score, $expected);
    }
}

