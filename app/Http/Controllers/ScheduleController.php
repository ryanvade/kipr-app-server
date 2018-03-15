<?php

namespace KIPR\Http\Controllers;

use KIPR\Match;
use KIPR\Competition;
use KIPR\Scheduling\Seeding;
use KIPR\Scheduling\DoubleElim;
use Illuminate\Http\Request;
use Carbon;


class ScheduleController extends Controller
{

    public function schedule(Competition $competition) {
        $teams = $competition->teams()->withPivot("signed_in")->where("signed_in", true)->get();

        // If no teams are signed in yet assume tenetive schedule
        if($teams->count() == 0)
            $teams = $competition->teams()->get();

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

        foreach(["seeding", "elimination"] as $round) {
            $firstMatch = $matches[$round][0]->match_time;
            $lastMatch = $matches[$round][0]->match_time;
            foreach($matches[$round] as $match) {
                array_push($schedule[$round], [
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
    public function updateSchedule(Competition $competition, Request $request) {
        // Delete the current schedule
        $competition->matches()->delete();

        // Calculate the new schedule
        $matches = $this->schedule($competition);

        // Commit the schedule to the DB
        $startTime = new Carbon\Carbon($competition->start_date);
        $seedingLength = 0;
        foreach(["seeding", "elimination"] as $round) {
            foreach($matches[$round] as $match) {
                if($match->team_A = "BYE") {
                    $match->team_A = 0;
                }
                if($match->team_B = "BYE") {
                    $match->team_B = 0;
                }
                unset($match->teamA);
                unset($match->teamB);

                if($match->match_time > $seedingLength) {
                    $seedingLength = $match->match_time;
                }

                if($round == "elimination") {
                    $match->match_time = $startTime->copy()->addMinutes(5 * ($match->match_time + $seedingLength) + 60);
                } else {
                    $match->match_time = $startTime->copy()->addMinutes(5 * $match->match_time);
                }

                $competition->matches()->save($match);
            }
        }

        $schedule = [];
        $schedule["seeding"] = [];
        $schedule["elimination"] = [];

        foreach(["seeding", "elimination"] as $round) {
            $firstMatch = $matches[$round][0]->match_time;
            $lastMatch = $matches[$round][0]->match_time;
            foreach($matches[$round] as $match) {
                array_push($schedule[$round], [
                    "match_id"=> $match->id,
                    "start_time" => $match->match_time,
                    "table_num" => $match->match_table]
                );
                $firstMatch = min($match->match_time, $firstMatch);
                $lastMatch = max($match->match_time, $lastMatch);
            }
        }

        return response()->json($schedule);
    }
}
