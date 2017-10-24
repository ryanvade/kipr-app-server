<?php

namespace KIPR;

use KIPR\Team;
use KIPR\Match;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    public function match()
    {
      return $this->belongsTo(Match::class);
    }

    public function team()
    {
      return $this->belongsTo(Team::class);
    }
}
