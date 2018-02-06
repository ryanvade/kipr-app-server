<?php

namespace KIPR\Http\Controllers;

use KIPR\Match;
use KIPR\Competition;
use KIPR\Scheduling\Seeding;
use KIPR\Scheduling\DoubleElim;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{

    public function schedule(Competition $competition) {
        $teams = $competition->teams()->withPivot("signed_in")->where("signed_in", true)->get();
        assert($teams->count() > 0);
        if($teams->count() == 0) {
            $x = 5/0;
        }

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
        foreach(["seeding", "elimination"] as $round) {
            foreach($matches[$round] as $match) {
                $competition->matches()->save($match);
            }
        }

        return response()->json(["status"=>"success", "matches"=>count($matches["seeding"]) + count($matches["elimination"])]);
    }
}
