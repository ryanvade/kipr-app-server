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
            $matchesFlat = $matchesFlat->concat($matches->where('match_type', 'seeding')->where('round', $r));
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
                $match->teamA()->associate($team);
                $match->match_type = "seeding";
                $match->round = $n;
                $matches->push($match);
            }
        }

        return $matches;
    }
}
