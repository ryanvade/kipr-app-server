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
