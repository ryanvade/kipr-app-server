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

    public function score(Rule $rule)
    {
      $results = $this->getParsedResults();
      $rules = $rule->getParsedRules();
      $counts = $rules->where('type','count');
      $mults = $rules->where('type','multiplier');
      $customs = $rules->where('type','custom');

      foreach($results as $teamResults)
      {
        //goes through each team

        foreach($counts as $countRule)
        {
          //gets the count for each object in a given location

        }

        foreach($mults as $multRule)
        {
          //gets the multipliers that need to be applied to a given
          //the count, object, and location

        }

        //need class to handle custom rules
        //scores are summed and added to a collection
      }
    }

    public function getParsedResults()
    {
      $results = collect(json_decode($this->results));


      foreach($results->keys() as $key)
      {
        $results[$key] = collect($results[$key]);
      }

      return $results;
    }
}
