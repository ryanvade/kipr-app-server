<?php

namespace KIPR\Http\Controllers;

use KIPR\Match;
use KIPR\Competition;
use KIPR\Scheduling\Seeding;
use KIPR\Scheduling\DoubleElim;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{

    public function __construct()
    {
      $this->middleware('auth:api');
    }

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
}
