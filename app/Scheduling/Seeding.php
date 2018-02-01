<?php

namespace KIPR\Scheduling;

use KIPR\Team;
use KIPR\Match;

class Seeding extends Bracket {

    public function createMatches($competition, $teams) {
        $teams = collect($teams);

        // Generate 3 seeding matches for all teams
        foreach ($teams as $team) {
            for($n = 0; $n < 3; $n++) {
                $competition->matches()->create([
                    'team_A' => $team->id,
                    'match_type' => "seeding",
                    'round' => $n
                ]);
            }
        }
    }
}

