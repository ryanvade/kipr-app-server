<?php

namespace KIPR;

use KIPR\Team;
use KIPR\Match;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    public function teams()
    {
      return $this->belongsToMany(Team::class);
    }

    public function matches()
    {
      return $this->hasMany(Match::class);
    }
}
