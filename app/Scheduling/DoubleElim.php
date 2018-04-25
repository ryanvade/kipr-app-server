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

class DoubleElim extends Bracket {

    public function scheduleMatches($matches) {
        $tables = 3;
        $rounds = 10;

        // Flatten the matches into a single list ordered by round number
        $matchesFlat = collect();
        for($r = 0; $r < $rounds; $r++) {
            $matchesFlat = $matchesFlat->concat($matches->where('match_type', 'WW')->where('round', $r));
            $matchesFlat = $matchesFlat->concat($matches->where('match_type', 'LL')->where('round', $r));
            $matchesFlat = $matchesFlat->concat($matches->where('match_type', 'WL')->where('round', $r));
        }
        $matchesFlat = $matchesFlat->concat($matches->where('match_type', 'double_elim_finals'));

        // Assign matches to table in a round robin fashion
        $timeslot = 0;
        $match = $matchesFlat->getIterator();
        while($match->valid()) {
            for($t = 0; $t < $tables; $t++) {
                if(!$match->valid()) break;
                // Skip byes
                while(!$match->current()->teamA() && !$match->current()->matchA()
                    || !$match->current()->teamB() && !$match->current()->matchB()) $match->next();

                // We can't schedule matches at the same time as their dependents
                if($match->current()->matchA && $match->current()->matchA->match_time >= $timeslot ||
                        $match->current()->matchB && $match->current()->matchB->match_time >= $timeslot)
                    continue;

                $match->current()->match_table = $t;
                $match->current()->match_time = $timeslot;
                $match->next();
            }
            $timeslot++;
        }
    }

    public function createMatches($competition, $teams) {
        $teams = collect($teams);
        $matches = collect([]);
        // Calculate bracket size
        $bracket_size = 1;
        $rounds = 0;
        while($bracket_size < count($teams))
        {
            $rounds++;
            $bracket_size = $bracket_size * 2;
        }

        // Calculate the number of matches and byes in round 1
        $match_count = $bracket_size/2;
        $byes = $bracket_size - count($teams);

        // Assign first round byes
        $bye_teams = $teams->random($byes);

        // Sort teams by seeding score
        array_sort($teams);

        // Generate first round
        $winner_matches = collect([]);
        $a = 0;
        $b = count($teams) - 1;
        $winner_matches->push(collect([]));

        while(count($winner_matches[0]) < $match_count) {
            $newMatch = new Match();

            // A has a bye
            if($bye_teams->has($teams[$a]->id)) {
                $newMatch->teamA()->associate($teams[$a]);
                $a++;

            // B has a bye
            } else if($bye_teams->has($teams[$b]->id)) {
                $newMatch->teamA()->associate($teams[$b]);
                $b--;

            // Neither team has a bye
            } else {
                $newMatch->teamA()->associate($teams[$a]);
                $newMatch->teamB()->associate($teams[$b]);
                $a++;
                $b--;
            }

            $newMatch->match_type = "WW";
            $newMatch->bracket_type = "de_winner";
            $newMatch->round = 0;
            $winner_matches[0]->push($newMatch);
            $matches->push($newMatch);
        }

        // Generate winners bracket
        for($r = 1; $r < $rounds; $r++) {
            $winner_matches->push(collect([]));
            for($m = 0; $m < 1 << ($rounds - $r); $m+=2) {
                $newMatch = new Match();
                $newMatch->matchAObj=($winner_matches[$r-1][$m]);
                $newMatch->matchBObj=($winner_matches[$r-1][$m+1]);

                $newMatch->match_type = "WW";
                $newMatch->bracket_type = "de_winner";
                $newMatch->round = $r;

                $winner_matches[$r]->push($newMatch);
                $matches->push($newMatch);
            }
        }

        // Generate losers bracket first round
        $deRounds = ($rounds - 1) * 2;
        $r = 0;
        $loser_matches = collect([]);
        $loser_matches->push(collect([]));
        for($m = 0; $m < 1 << (($deRounds - $r) / 2); $m+=2) {
            $newMatch = new Match();
            $newMatch->match_A = -1;
            $newMatch->matchAObj=($winner_matches[$r][$m]);
            $newMatch->match_B = -1;
            $newMatch->matchBObj=($winner_matches[$r][$m+1]);

            $newMatch->match_type = "LL";
            $newMatch->bracket_type = "de_loser";
            $newMatch->round = $r;

            $loser_matches[$r]->push($newMatch);
            $matches->push($newMatch);
        }

        $r = 1;
        $loser_matches->push(collect([]));
        for($m = 0; $m < 1 << (($deRounds - $r) / 2); $m++) {
            $newMatch = new Match();
            $newMatch->matchAObj=($loser_matches[0][$m]);
            $newMatch->matchBObj=($winner_matches[1][$m]);

            $newMatch->match_type = "WL";
            $newMatch->bracket_type = "de_loser";
            $newMatch->round = $r;

            $loser_matches[$r]->push($newMatch);
            $matches->push($newMatch);
        }

        // Generate losers bracket
        for($r = 2; $r < $deRounds; $r++) {
            $loser_matches->push(collect([]));
            for($m = 0; $m < 1 << (($deRounds - $r) / 2); $m+=2) {
                $newMatch = new Match();
                $newMatch->matchAObj=($loser_matches[$r-1][$m]);
                $newMatch->matchBObj=($loser_matches[$r-1][$m+1]);

                $newMatch->match_type = "WW";
                $newMatch->bracket_type = "de_loser";
                $newMatch->round = $r;

                $loser_matches[$r]->push($newMatch);
                $matches->push($newMatch);
            }

            $r++;
            $loser_matches->push(collect([]));
            for($m = 0; $m < 1 << (($deRounds - $r) / 2); $m++) {
                $newMatch = new Match();
                $newMatch->matchAObj=($loser_matches[$r-1][$m]);
                $newMatch->matchBObj=($winner_matches[$r/2][$m]);

                $newMatch->match_type = "WL";
                $newMatch->bracket_type = "de_loser";
                $newMatch->round = $r;

                $loser_matches[$r]->push($newMatch);
                $matches->push($newMatch);
            }
        }

        // Should be a single match in the last round of the winner's and losers bracket
        assert(count($loser_matches[$deRounds - 1]) == 1);
        assert(count($winner_matches[$rounds - 1]) == 1);

        // Generate the first round of the finals match
        $newMatch = new Match();
        $newMatch->matchAObj=($winner_matches[$rounds-1][0]);
        $newMatch->matchBObj=($loser_matches[$deRounds-1][0]);
        $newMatch->match_type = "WW";
        $newMatch->bracket_type = "final";
        $newMatch->round = 0;
        $matches->push($newMatch);

        return $matches;
    }
}
