<?php

namespace KIPR;

use KIPR\Match;
use KIPR\Competition;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    public function competitions()
    {
      return $this->belongsToMany(Competition::class);
    }

    public function matches()
    {
      return $this->belongsToMany(Match::class);
    }
}
