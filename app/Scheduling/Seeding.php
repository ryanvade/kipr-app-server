<?php

namespace KIPR\Scheduling;

use KIPR\Team;
use KIPR\Match;

class Seeding extends Bracket {

    public function scheduleMatches($matches) {
        $tables = 3;
        $rounds = 3;

        // Flatten the matches into a single list ordered by round number
        $matchesFlat = collect();
        for($r = 0; $r < $rounds; $r++) {
            $matchesFlat = $matchesFlat->union($matches->where('match_type', 'seeding')->where('round', '$r'));
        }

        // Assign matches to table in a round robin fashion
        $timeslot = 0;
        $match = $matchesFlat->getIterator();
        while($match->valid()) {
            for($t = 0; $t < $tables; $t++) {
                if(!$match->valid()) break;
                $match->current()->match_table = $t;
                $match->current()->match_time = $timeslot;
                $match->next();
            }
            $timeslot++;
        }
    }

    public function createMatches($competition, $teams) {
        $teams = collect($teams);
        $matches = collect();

        // Generate 3 seeding matches for all teams
        foreach ($teams as $team) {
            for($n = 0; $n < 3; $n++) {
                $match = new Match();
                $match->team_A = $team->id;
                $match->match_type = "seeding";
                $match->round = $n;
                $matches->push($match);
            }
        }

        return $matches;
    }
}

