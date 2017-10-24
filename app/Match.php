<?php

namespace KIPR;

use KIPR\Team;
use KIPR\Competition;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    public function teams()
    {
      return $this->belongsToMany(Team::class);
    }

    public function competition()
    {
      return $this->belongsTo(Competition::class);
    }
}
