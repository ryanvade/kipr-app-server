<?php

namespace KIPR;

use KIPR\Team;
use KIPR\Competition;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $fillable = [
      'match_time',
      'competition_id',
      'results',
      'team_A',
      'team_B'
    ];

    public function teamA()
    {
        return $this->belongsTo(Team::class, 'team_A');
    }

    public function teamB()
    {
        return $this->belongsTo(Team::class, 'team_B');
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function setFinalScore(Score $score) {
        $this->results = score;
    }
}
