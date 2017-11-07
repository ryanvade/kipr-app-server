<?php

namespace KIPR\Judging;

use KIPR\Team;
use KIPR\Match;
use KIPR\Score;
use KIPR\Competition;
use Illuminate\Contracts\Support\Jsonable;

// TODO Make this a singleton?
class Tabulator 
{
    // Take match
    // Return score
    public static function score(Match $match, Rule $rule = null)
    {
        if ($rule == null) {
            $rule = $match->competition->rule;
        }

        $results = $this->getParsedResults();
        $rules = $rule->getParsedRules();
        $counts = $rules->where('type', 'count');
        $mults = $rules->where('type', 'multiplier');
        $customs = $rules->where('type', 'custom');

        foreach ($results as $teamResults) {
            //goes through each team

            foreach ($counts as $countRule) {
                //gets the count for each object in a given location
            }

            foreach ($mults as $multRule) {
                //gets the multipliers that need to be applied to a given
          //the count, object, and location
            }

            //need class to handle custom rules
        //scores are summed and added to a collection
        }
    }

    public static function getParsedResults()
    {
        $results = collect(json_decode($this->results));


        foreach ($results->keys() as $key) {
            $results[$key] = collect($results[$key]);
        }

        return $results;
    }
}
