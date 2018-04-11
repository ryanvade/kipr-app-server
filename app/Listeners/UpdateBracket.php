<?php

namespace KIPR\Listeners;

use KIPR\Events\MatchScored;
use KIPR\Events\MatchReady;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateBracket
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  MatchScored  $event
     * @return void
     */
    public function handle(MatchScored $event)
    {
        $match = $event->match;
        $match->results = json_decode($match->results);
        $competition = $match->competition()->first();

        if($match->match_type == "seeding") {
            info("Scored seeding match");
            $team = $match->teamA()->first();
            info($team);
            $teamPivot = $competition->teams()->where('team_id', $team->id)->first();
            if ($teamPivot == null) {
                return response()->json([
                'status' => 'error',
                'message' => 'the team is not registered with the competition'
              ], 409);
            }

            $teamPivot->pivot->seeding = max($teamPivot->pivot->seeding, $match->results->score);
            $teamPivot->pivot->save();
        } else {
            $winner = $match->results->winner;
            $loser = $match->results->loser;

            // Get all the matches that depend on this match
            $depend = $competition->matches()
                ->where([["team_A", ""], ["match_A", $match->id]])
                ->orWhere([["team_B", null], ["match_B", $match->id]])->get();

            foreach($depend as $m) {
                if($m->match_A == $match->id) {
                    $m->teamA()->associate(($m->match_type == "WW" || $m->match_type == "WL") ? $winner : $loser);
                    $m->match_A = 0;
                }

                else if($m->match_B == $match->id) {
                    $m->teamB()->associate(($m->match_type == "LL" || $m->match_type == "WL") ? $loser : $winner);
                    $m->match_B = 0;
                }

                if($match->teamA && $match->teamB)
                    event(new MatchReady($match));
                info($m);
                
                $m->save();
            }
        }
    }
}
