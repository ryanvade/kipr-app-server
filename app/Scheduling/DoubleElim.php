<?php

namespace KIPR\Scheduling;

use KIPR\Team;
use KIPR\Match;

class DoubleElim extends Bracket {

    public function scheduleMatches($matches) {
        $tables = 3;
        $rounds = 5;

        // Flatten the matches into a single list ordered by round number
        $matchesFlat = collect();
        for($r = 0; $r < $rounds; $r++) {
            $matchesFlat = $matchesFlat->union($matches->where('match_type', 'double_elim_win')->where('round', '$r'));
            $matchesFlat = $matchesFlat->union($matches->where('match_type', 'double_elim_lose')->where('round', '$r'));
        }
        $matchesFlat = $matchesFlat->union($matches->where('match_type', 'double_elim_finals'));

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
                $newMatch->team_A = $teams[$a]->id;
                $newMatch->team_B = "BYE";
                $a++;

            // B has a bye
            } else if($bye_teams->has($teams[$b]->id)) {
                $newMatch->team_A = $teams[$b]->id;
                $newMatch->team_B = "BYE";
                $b--;

            // Neither team has a bye
            } else {
                $newMatch->team_A = $teams[$a]->id;
                $newMatch->team_B = $teams[$b]->id;
                $a++;
                $b--;
            }

            $newMatch->match_type = "double_elim_win";
            $newMatch->round = 0;
            $winner_matches[0]->push($newMatch);
            $matches->push($newMatch);
        }

        // Generate winners bracket
        for($r = 1; $r < $rounds; $r++) {
            $winner_matches->push(collect([]));
            for($m = 0; $m < 1 << ($rounds - $r); $m+=2) {
                $newMatch = new Match();
                $newMatch->match_A = $winner_matches[$r-1][$m]->id;
                $newMatch->match_B = $winner_matches[$r-1][$m+1]->id;
                $newMatch->team_A = "Pending";
                $newMatch->team_B = "Pending";

                $newMatch->match_type = "double_elim_win";
                $newMatch->round = $r;

                $winner_matches[$r]->push($newMatch);
                $matches->push($newMatch);
            }
        }

        // Generate losers bracket
        $loser_matches = collect([]);
        $loser_matches->push(collect([]));
        for($r = 1; $r < $rounds; $r++) {
            $loser_matches->push(collect([]));
            for($m = 0; $m < 1 << ($rounds - $r); $m+=2) {
                $newMatch = new Match();
                $newMatch->match_A = $winner_matches[$r-1][$m]->id;
                $newMatch->match_B = $winner_matches[$r-1][$m+1]->id;
                $newMatch->team_A = "Pending";
                $newMatch->team_B = "Pending";

                $newMatch->match_type = "double_elim_lose";
                $newMatch->round = $r;

                $loser_matches[$r]->push($newMatch);
                $matches->push($newMatch);
            }
        }

        // Should be a single match in the last round of the winner's and losers bracket
        assert(count($loser_matches[$rounds - 1]) == 1);
        assert(count($winner_matches[$rounds - 1]) == 1);

        // Generate the first round of the finals match
        $newMatch = new Match();
        $newMatch->match_A = $winner_matches[$rounds - 1][0]->id;
        $newMatch->match_B = $loser_matches[$rounds-1][0]->id;
        $newMatch->team_A = "Pending";
        $newMatch->team_B = "Pending";
        $newMatch->match_type = "double_elim_finals";
        $newMatch->round = 0;
        $matches->push($newMatch);

        return $matches;
    }
}

