<?php

namespace KIPR\Listeners\App\Listeners;

use KIPR\Events\App\Events\MatchScored;
use KIPR\Events\App\Events\MatchReady;
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
        $winner = $match->winner;
        $loser = $match->winner == $match->teamA ? $match->teamB : $match->teamB;

        // Get all the matches that depend on this match
        $depend = match->competition->matches()
            ->where([["team_A", ""], ["match_A", $match->id]])
            ->orWhere([["team_B", ""], ["match_B", $match->id]])->get();

        foreach($depend as $m) {
            if($m->matchA == $match)
                $m->teamA = $winner;

            if($m->matchB == $match)
                $m->teamB = $winner;

            if($match->teamA && $match->teamB)
                event(new MatchReady($match));
            
            $m->save();
        }
    }
}
