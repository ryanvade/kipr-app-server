<?php

namespace KIPR\Judging;

use KIPR\Team;
use KIPR\Match;
use KIPR\Competition;
use Illuminate\Contracts\Support\Jsonable;

class Score implements Jsonable
{
    private $team_A = null;
    private $team_A_score = 0;
    private $team_B = null;
    private $team_B_score = 0;
    private $match = null;

    public function __construct()
    {
    }

    public function toJson($options = 0)
    {
        $json = [];
        if ($this->team_A) {
            $this->team_A->score = $this->team_A_score;
            $json["team_A"] = $this->team_A->toJson();
        }

        if ($this->team_B) {
            $this->team_B->score = $this->team_B_score;
            $json["team_B"] = $this->team_B->toJson();
        }

        if ($this->match) {
            $json["match"] = $this->match->toJson();
        }

        return json_encode($json, $options);
    }
}
