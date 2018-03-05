<?php

namespace KIPR\Http\Controllers;

use DB;
use KIPR\Team;
use KIPR\Match;
use KIPR\Ruleset;
use KIPR\Competition;
use KIPR\Judging\Tabulator;
use Illuminate\Http\Request;
use KIPR\Filters\MatchFilter;
use KIPR\Exceptions\InvalidResultException;

class MatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', [
          'except' => [
            'getAll'
          ]
        ]);
    }

    public function getMatchCount()
    {
        $count = Match::count();
        return response()->json([
        'status' => 'success',
        'match_count' => $count
      ]);
    }

    public function getAll(Request $request) {
      $filter = new MatchFilter($request);
      $teams = $filter->apply(DB::table('matches'));
      $results = $teams->paginate(20);
      foreach ($results->items() as $match) {
        $match->teamA = Team::find($match->team_A);
        $match->teamB = Team::find($match->team_B);
        $match->competition = Competition::find($match->competition_id);
      }
      return $results;
    }

    public function score(Competition $competition, Match $match, Request $request)
    {
        $request->validate([
          'results' => 'bail|required|json'
        ]);

        if ($competition->ruleset()->first() == null) {
            return response()->json([
              'status' => 'error',
              'message' => 'competition does not have a ruleset'
            ], 412);
        }

        try {
          $results = Tabulator::scoreMatch($competition->ruleset, json_decode($request->results, true));
        } catch (InvalidResultException $e) {
          return response()->json([
            'status' => 'error',
            'message' => 'results array is not valid'
          ], 400);
        }

        $match->setResults($results);
        return response()->json([
        'status' => 'success',
        'message' => 'match scored',
        'results' => $results
      ]);
    }
}
