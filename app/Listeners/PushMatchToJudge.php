<?php

namespace KIPR\Listeners\App\Listeners;

use KIPR\Events\App\Events\MatchScored;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PushMatchToJudge
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
        $finished = $event->match;
        $next = finished->competition->matches()
            ->where([["match_table", $finished->match_table],
                ["match_time", ">", $finished->match_time])
            ->orderBy("match_time")
            ->first();

        // Push $next to the table judge on table $next->match_table
        event(new MatchSentToTable($next, $next->match_table));
    }
}
