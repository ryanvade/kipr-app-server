<?php

namespace KIPR\Filters;

use DB;
use KIPR\Team;
use KIPR\Competition;

class CompetitionFilter extends Filter {


  protected $filters = [
    'registered',
    'name'
  ];

  protected function registered($registered) {
    $exp = explode(',', $registered);
    if(count($exp) < 2) {
      return $this->builder;
    }

    $team = Team::find($exp[0]);
    if($team == null) {
      return $this->builder;
    }

    if(boolval($exp[1])) {
      // where they are registered
      return $this->builder->join('competition_teams', 'competitions.id', '=', 'competition_teams.competition_id')
                           ->where('team_id', '=', $team->id);
    }else {
      $ids = $team->competitions()->pluck('competitions.id');
      return $this->builder->whereNotIn('competitions.id', $ids);
    }
  }

  protected function name($name) {
    return $this->builder->where("competitions.name", "like", $name . "%");
  }
}
