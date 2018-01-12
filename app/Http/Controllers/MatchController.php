<?php

namespace KIPR\Http\Controllers;

use KIPR\Match;
use KIPR\Judging\Score;
use KIPR\Juding\Tabulator;
use Illuminate\Http\Request;

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

    public function getMatchCount() {
      $count = Match::count();
      return response()->json([
        'status' => 'success',
        'match_count' => $count
      ]);
    }
}
