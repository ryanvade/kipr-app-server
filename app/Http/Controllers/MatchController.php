<!-- Copyright (c) 2018 KISS Institute for Practical Robotics

BSD v3 License

All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this
  list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright notice,
  this list of conditions and the following disclaimer in the documentation
  and/or other materials provided with the distribution.

* Neither the name of KIPR Scoring App nor the names of its
  contributors may be used to endorse or promote products derived from
  this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. -->
<?php

namespace KIPR\Http\Controllers;

use DB;
use KIPR\Team;
use KIPR\Match;
use KIPR\Ruleset;
use KIPR\Competition;
use KIPR\Judging\Tabulator;
use KIPR\Judging\Score;
use KIPR\Events\MatchScored;
use KIPR\Http\Requests\ScoreMatch;
use Illuminate\Http\Request;
use KIPR\Filters\MatchFilter;
use KIPR\Exceptions\InvalidResultException;

class MatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', [
          'except' => [
              'getAll',
              'get',
              'count'
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
        $match->results = json_decode($match->results);
      }
      return $results;
    }

    public function get(Match $match)
    {
        $match->results = json_decode($match->results);
        return $match;
    }

    public function score(Competition $competition, Match $match, ScoreMatch $request)
    {
        $results=$request->results;

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
        event(new MatchScored($match));
        return response()->json([
        'status' => 'success',
        'message' => 'match scored',
        'results' => $results
      ]);
    }
}
