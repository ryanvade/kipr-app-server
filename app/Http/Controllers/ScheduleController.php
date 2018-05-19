<?php
/*
 Copyright (c) 2018 KISS Institute for Practical Robotics

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
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
namespace KIPR\Http\Controllers;

use KIPR\Match;
use KIPR\Competition;
use KIPR\Scheduling\Seeding;
use KIPR\Scheduling\DoubleElim;
use KIPR\Events\MatchScored;
use Illuminate\Http\Request;
use Carbon;

class ScheduleController extends Controller
{
    public function schedule(Competition $competition)
    {
        $teams = $competition->teams()->withPivot("signed_in")->where("signed_in", true)->get();

        // If no teams are signed in yet assume tenetive schedule
        if ($teams->count() == 0) {
            $teams = $competition->teams()->get();
        }

        assert($teams->count() > 0);

        // Create seeding matches
        $seeding = new Seeding();
        $seedingMatches = $seeding->createMatches($competition, $teams);
        $seeding->scheduleMatches($seedingMatches);

        // Create DE matches
        $deBracket = new DoubleElim();
        $deMatches = $deBracket->createMatches($competition, $teams);
        $deBracket->scheduleMatches($deMatches);

        return [
            'seeding' => $seedingMatches,
            'elimination' => $deMatches
        ];
    }

    /**
     * Gets a tenetive schedule including all the teams that are currently signed in.
     */
    public function getSchedule(Competition $competition, Request $request)
    {
        $matches = $this->schedule($competition);

        $schedule = [];
        $schedule["seeding"] = [];
        $schedule["elimination"] = [];

        foreach (["seeding", "elimination"] as $round) {
            $firstMatch = $matches[$round][0]->match_time;
            $lastMatch = $matches[$round][0]->match_time;
            foreach ($matches[$round] as $match) {
                array_push(
                    $schedule[$round],
                    [
                    "match_id"=> $match->id,
                    "start_time" => $match->match_time,
                    "table_num" => $match->match_table]
                );
                $firstMatch = min($match->match_time, $firstMatch);
                $lastMatch = max($match->match_time, $lastMatch);
            }
            $schedule[$round]["length"] = $lastMatch - $firstMatch;
        }

        return response()->json($schedule);
    }

    /**
     * Generates the match schedule and saves it in the database
     */
    public function updateSchedule(Competition $competition, Request $request)
    {
        // Delete the current schedule
        $competition->matches()->delete();

        // Calculate the new schedule
        $matches = $this->schedule($competition);

        // Commit the schedule to the DB
        $startTime = new Carbon\Carbon($competition->start_date);
        $seedingLength = 0;
        foreach (["seeding", "elimination"] as $round) {
            foreach ($matches[$round] as $match) {
                if ($match->matchAObj) {
                    info("Object A");
                    $competition->matches()->save($match->matchAObj);

                    if ($match->team_A < 0) {
                    } else {
                    }
                    $match->matchA()->associate($match->matchAObj);
                    unset($match->matchAObj);
                }
                if ($match->matchBObj) {
                    info("Object B");
                    $match->matchB()->associate($match->matchBObj);
                    $competition->matches()->save($match->matchBObj);
                    unset($match->matchBObj);
                }

                if (!isset($match->team_A)) {
                    $match->team_A = 0;
                }

                if ($match->match_time > $seedingLength) {
                    $seedingLength = $match->match_time;
                }

                if ($round == "elimination") {
                    $match->match_time = $startTime->copy()->addMinutes(5 * ($match->match_time + $seedingLength) + 60);
                } else {
                    $match->match_time = $startTime->copy()->addMinutes(5 * $match->match_time);
                }

                $competition->matches()->save($match);
            }
        }

        foreach ($matches["elimination"] as $match) {
            info($match->id);
            if ($match->match_A < 1) {
                if ($match->team_A < 1) {
                    // BYE for B
                    $match->results = "{\"score\": 0, \"winner\": $match->team_B, \"loser\": 0}";
                    $competition->matches()->save($match);
                    event(new MatchScored($match));
                }
            }
            if ($match->match_B < 1) {
                if ($match->team_B < 1) {
                    // BYE for A
                    $match->results = "{\"score\": 0, \"winner\": $match->team_A, \"loser\": 0}";
                    $competition->matches()->save($match);
                    info("BYE FOR A");
                    info($match);
                    event(new MatchScored($match));
                }
            }
        }

        //$schedule = [];

        //foreach(["seeding", "elimination"] as $round) {
        //$firstMatch = $matches[$round][0]->match_time;
        //$lastMatch = $matches[$round][0]->match_time;
        //foreach($matches[$round] as $match) {
        //array_push($schedule, [
        //"match_id"=> $match->id,
        //"start_time" => $match->match_time,
        //"table_num" => $match->match_table]
        //);
        //$firstMatch = min($match->match_time, $firstMatch);
        //$lastMatch = max($match->match_time, $lastMatch);
        //}
        //}

        return $competition->matches()->get();
    }
}
