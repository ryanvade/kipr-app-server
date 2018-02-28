<?php

namespace KIPR\Filters;

use DB;
use KIPR\Team;
use KIPR\Competition;

class TeamFilter extends Filter {


  protected $filters = [
    'registered',
    'code',
    'name'
  ];

  protected function registered($registered) {
    $exp = explode(',', $registered);
    if(count($exp) < 2) {
      return $this->builder;
    }

    $comp = Competition::find($exp[0]);
    if($comp == null) {
      return $this->builder;
    }

    if(boolval($exp[1])) {
      // where they are registered
      return $this->builder->join('competition_teams', 'teams.id', '=', 'competition_teams.team_id')
                           ->where('competition_id', '=', $comp->id);
    }else {
      $ids = $comp->teams()->pluck('teams.id');
      return $this->builder->whereNotIn('teams.id', $ids);
    }
  }

  protected function code($code) {
    return $this->builder->where('teams.code', $code);
  }

  protected function name($name) {
    return $this->builder->where("teams.name", "like", $name . "%");
  }
}
