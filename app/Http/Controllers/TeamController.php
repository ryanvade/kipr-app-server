<?php

namespace KIPR\Http\Controllers;

use KIPR\Team;
use Carbon\Carbon;
use KIPR\Competition;
use KIPR\CompetitionTeam;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function signIn(Competition $competition, Team $team, Request $request)
    {
        $team = $competition->teams()->where('team_id', $team->id)->firstOrFail();
        if ($team->pivot->signed_in != true) {
            $team->pivot->signed_in = true;
            $team->pivot->sign_in_at = Carbon::now();
            $team->save();
        }
        return response()->json([
        'team_id' => $team->id,
        'competition_id' => $competition->id,
        'sign_in_time' => $team->pivot->sign_in_at
      ]);
    }
}
