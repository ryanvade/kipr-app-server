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

namespace KIPR\Http\Controllers;

use DB;
use KIPR\Team;
use Carbon\Carbon;
use KIPR\Competition;
use KIPR\Filters\TeamFilter;
use Illuminate\Http\Request;
use KIPR\Events\TeamSignedIn;
use KIPR\Http\Requests\CreateTeam;
use KIPR\Http\Requests\UpdateTeam;

class TeamController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth:api', [
        'except' => [
          'getTeamsAtCompetition',
          'getTeamCount',
          'getAll',
          'get',
          'seed',
        ]
      ]);
    }
    /**
     * Sign In - Sign in a team to a competition
     * @param  Competition $competition Competition to sign the team into
     * @param  Team        $team        Team to be signed in
     * @param  Request     $request     HTTP Request
     * @return JSON                     JSON Response
     */
    public function signIn(Competition $competition, Team $team, Request $request)
    {
        $teamPivot = $competition->teams()->where('team_id', $team->id)->first();
        if ($teamPivot == null) {
            return response()->json([
            'status' => 'error',
            'message' => 'the team is not registered with the competition'
          ], 409);
        }

        if ($teamPivot->pivot->signed_in != true) {
            $teamPivot->pivot->signed_in = true;
            $teamPivot->pivot->sign_in_time = Carbon::now();
            $teamPivot->pivot->save();
        }
        event(new TeamSignedIn($team));
        return response()->json([
        'team_id' => $team->id,
        'competition_id' => $competition->id,
        'sign_in_time' => $teamPivot->pivot->sign_in_at
      ]);
    }

    public function seed(Competition $competition, Team $team, Request $request)
    {
        info($competition->id);
        info($team->id);
        $teamPivot = $competition->teams()->where('team_id', $team->id)->first();
        if ($teamPivot == null) {
            return response()->json([
            'status' => 'error',
            'message' => 'the team is not registered with the competition'
          ], 409);
        }

        return response()->json([
            'team_id' => $team->id,
            'competition_id' => $competition->id,
            'seeding_score' => $teamPivot->pivot->seeding
        ]);
    }

    public function getTeamCount()
    {
        $count = Team::count();
        return response()->json([
        'status' => 'success',
        'team_count' => $count
      ]);
    }

    public function create(CreateTeam $request)
    {
        $team = Team::create([
        'name' => $request->name,
        'email' => $request->email,
        'code' => $request->code
      ]);
        return response()->json([
        'status' => 'success',
        'team' => $team
      ]);
    }

    public function getAll(Request $request)
    {
        $filter = new TeamFilter($request);
        $teams = $filter->apply(DB::table('teams'));
        return $teams->paginate(20);
    }

    public function get(Team $team)
    {
        return $team;
    }

    public function getTeamsAtCompetition(Competition $competition, Request $request)
    {
        // Validate the request
        $requestData = $request->validate([
          'signed_in' => 'boolean'
        ]);
        // Filter by signed in
        if (array_has($requestData, 'signed_in')) {
            $signed_in = array_get($requestData, 'signed_in');
            return $competition->teams()->withPivot('signed_in')->where('signed_in', $signed_in)->get();
        }
        // No Filtering, return all registered teams
        return $competition->teams()->paginate(20);
    }

    public function delete(Team $team)
    {
        $team->delete();
        return response()->json([
        'status' => 'success',
        'message' => $team->id . ' deleted'
      ]);
    }

    public function patch(Team $team, UpdateTeam $request)
    {
        $team->update([
        'name' => $request->name,
        'email' => $request->email,
        'code' => $request->code
      ]);
        return response()->json([
        'status' => 'success',
        'team' => $team
      ]);
    }

    public function massUpload(Request $request)
    {
        $request->validate([
        'file' => 'required|file'
      ]);
        $file = $request->file;
        $count = 0;
        $invalid = 0;
        foreach (file($file) as $line) {
            $values = explode(",", $line);
            if (count($values) == 3) {
                if (Team::where('code', $values[0])->count() > 0) {
                    $invalid++;
                } else {
                    Team::create([
              'code' => $values[0],
              'name' => $values[1],
              'email' => $values[2]
            ]);
                    $count++;
                }
            }
        }
        return response()->json([
        'status' => 'success',
        'message' => $count . ' teams added',
        'ignored' => $invalid . ' teams ignored'
      ]);
    }
}
