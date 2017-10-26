# example ruleset

```
[
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
]
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
