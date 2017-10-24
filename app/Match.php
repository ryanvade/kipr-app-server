<?php

namespace KIPR;

use KIPR\Team;
use KIPR\Score;
use KIPR\Competition;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $fillable = [
      'match_time',
      'competition_id'
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function scores()
    {
      return $this->hasMany(Score::class);
    }
}
