<?php

namespace KIPR\Http\Controllers;

use KIPR\Match;
use KIPR\Judging\Score;
use KIPR\Juding\Tabulator;
use Illuminate\Http\Request;
use KIPR\Judging\Tabulator;
use KIPR\Ruleset;

class MatchController extends Controller
{

    public function __construct()
    {
      $this->middleware('auth:api');
    }
    /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  \KIPR\Match  $match
      * @return \Illuminate\Http\Response
      */
    public function update(Match $match, Request $request)
    {
        # check if the PATCH is valid
        $isValid = $request->validate([
          'results' => 'required|json'
        ]);
        # save the match results
        $match->setResults($request->results);
        # get the score from the match
        $score = $match->score();
        # return the results
        return response()->json([
          'status' => 'scored',
          'score' => $score
        ]);
    }

    public function score(Request $request) {
        if(!$request->has("results"))
            abort(400, "Missing results field");

        if(!$request->has("ruleset_id"))
            abort(400, "Missing ruleset_id field");

        $json = $request->input("results");
        $results = json_decode($json, true);

        $ruleset = Ruleset::findOrFail($request->input("ruleset_id"));
        $results = Tabulator::scoreMatch($ruleset, $results);

        return response()->json($results);
    }
}

