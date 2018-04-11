<?php

namespace KIPR;

use KIPR\Team;
use KIPR\Competition;
use KIPR\Events\MatchCreated;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $fillable = [
      'match_type',
      'round',
      'match_time',
      'match_table',
      'competition_id',
      'results',
      'winner',
      'team_A',
      'team_B',
      'match_A',
      'match_B',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => MatchCreated::class,
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

    public function winner()
    {
        return $this->belongsTo(Team::class, 'winner');
    }

    public function matchA()
    {
        return $this->belongsTo(Match::class, 'match_A');
    }

    public function matchB()
    {
        return $this->belongsTo(Match::class, 'match_B');
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
