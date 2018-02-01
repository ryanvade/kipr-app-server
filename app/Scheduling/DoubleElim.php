<?php

namespace KIPR\Scheduling;

use KIPR\Team;
use KIPR\Match;

class DoubleElim extends Bracket {

    public function createMatches($competition, $teams) {
        $teams = collect($teams);
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
        $matches = [];
        $a = 0;
        $b = count($teams) - 1;
        array_push($matches, []);
        while(count($matches[0]) < $match_count) {
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
            array_push($matches[0], $newMatch);
            $competition->matches()->save($newMatch);
        }

        // Generate winners bracket
        for($r = 1; $r < $rounds; $r++) {
            array_push($matches, []);
            for($m = 0; $m < 1 << ($rounds - $r); $m+=2) {
                $newMatch = new Match();
                $newMatch->match_A = $matches[$r-1][$m]->id;
                $newMatch->match_B = $matches[$r-1][$m+1]->id;
                $newMatch->team_A = "Pending";
                $newMatch->team_B = "Pending";

                $newMatch->match_type = "double_elim_win";
                $newMatch->round = $r;
                array_push($matches[$r], $newMatch);
                $competition->matches()->save($newMatch);
            }
        }

        // Generate losers bracket
        $loser_matches = [];
        array_push($loser_matches, []);
        for($r = 1; $r < $rounds; $r++) {
            array_push($loser_matches, []);
            for($m = 0; $m < 1 << ($rounds - $r); $m+=2) {
                $newMatch = new Match();
                $newMatch->match_A = $matches[$r-1][$m]->id;
                $newMatch->match_B = $matches[$r-1][$m+1]->id;
                $newMatch->team_A = "Pending";
                $newMatch->team_B = "Pending";

                $newMatch->match_type = "double_elim_lose";
                $newMatch->round = $r;
                array_push($loser_matches[$r], $newMatch);
                $competition->matches()->save($newMatch);
            }
        }

        // Should be a single match in the last round of the winner's and losers bracket
        assert(count($loser_matches[$rounds - 1]) == 1);
        assert(count($matches[$rounds - 1]) == 1);

        // Generate the first round of the finals match
        $newMatch = new Match();
        $newMatch->match_A = $matches[$rounds - 1][0]->id;
        $newMatch->match_B = $loser_matches[$rounds-1][0]->id;
        $newMatch->team_A = "Pending";
        $newMatch->team_B = "Pending";
        $newMatch->match_type = "double_elim_finals";
        $newMatch->round = 0;
        $competition->matches()->save($newMatch);

        return $matches;
    }
}

