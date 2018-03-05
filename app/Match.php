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

    protected $with = [
      'teamA',
      'teamB',
      'competition'
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

    public function setFinalScore(Score $score)
    {
        $this->results = score;
    }

    public function setResults($results)
    {
        if (is_array($results)) {
            $results = json_encode($results);
        }
        $this->update([
        'results' => $results
      ]);
        # TODO: Fire Event for match end
    }
}
