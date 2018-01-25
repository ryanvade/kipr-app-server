<?php

namespace KIPR\Http\Controllers;

use KIPR\Match;
use KIPR\Ruleset;
use KIPR\Competition;
use KIPR\Judging\Tabulator;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getMatchCount()
    {
        $count = Match::count();
        return response()->json([
        'status' => 'success',
        'match_count' => $count
      ]);
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

        if(!Tabulator::isValid($competition->ruleset, json_decode($request->results, true))) {
          return response()->json([
            'status' => 'error',
            'message' => 'results array is not valid'
          ], 400);
        }

        $results = Tabulator::scoreMatch($competition->ruleset, json_decode($request->results, true));
        $match->setResults($results);
        return response()->json([
        'status' => 'success',
        'message' => 'match scored',
        'results' => $results
      ]);
    }
}
