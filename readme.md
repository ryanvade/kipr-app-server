# example ruleset

```
{
    "events": {
        "robot_on_terrace": { "min" : 0, "max" : 2  },
        "red_ball_in_cup":  { "min" : 0, "max" : 10 },
        "blue_ball_in_cup": { "min" : 0, "max" : 10 },
    ],
    "rules": [
        {
            "type": "multiplier",
            "target": "robot_on_terrace",
            "value": 15,
        },
        {
            "type": "multiplier",
            "target": "red_ball_in_cup",
            "value": 1,
        },
        {
            "type": "multiplier",
            "target": "blue_ball_in_cup",
            "value": 1,
        },
        {
            "type": "conditional",
            "target": "blue_ball_in_cup == red_ball_in_cup",
            "value": [ { "type": "fixed", "target": "balls_matched", "value" : 20 } ]
        },
        {
            "type": "conditional",
            "target": "robot_on_terrace == 2",
            "value": [ { "type": "multiplier", "target": "robot_on_terrace", "value" : 2 } ]
        }
    ]
}


```

# example match result

```
[
       "A" => [
           "robot_terrace" => 1,
           "golfball_cup" => 4,
       ],
       "B" => [
           "robot_terrace" => 0,
           "golfball_cup" => 2,
       ],
],

```
