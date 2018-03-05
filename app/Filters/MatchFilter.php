<?php

namespace KIPR\Filters;

use DB;
use KIPR\Team;
use KIPR\Match;
use KIPR\Competition;

class MatchFilter extends Filter {

  protected $filters = [
    'scored',
    'teamA',
    'teamB',
    'competition'
  ];

  public function scored($scored) {
    if($scored) {
      return $this->builder->where('results', '!=', null);
    }else {
      return $this->builder->where('results', null);
    }
  }

  public function teamA($teamA) {
    return $this->builder->where('team_A', $teamA);
  }

  public function teamB($teamB) {
    return $this->builder->where('team_B', $teamB);
  }

  public function competition($competition) {
    return $this->builder->where('competition_id', $competition);
  }

}
