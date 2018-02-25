<?php

namespace KIPR\Http\Controllers;

use KIPR\Team;
use Carbon\Carbon;
use KIPR\Competition;
use Illuminate\Http\Request;
use KIPR\Http\Requests\CreateTeam;
use KIPR\Http\Requests\UpdateTeam;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only([
          'create',
          'delete',
          'patch',
          'massUpload'
        ]);

        $this->middleware('auth:api')->only([
          'signIn'
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
        return response()->json([
        'team_id' => $team->id,
        'competition_id' => $competition->id,
        'sign_in_time' => $teamPivot->pivot->sign_in_at
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

    public function getAll()
    {
        return Team::paginate(20);
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
        if(array_has($requestData, 'signed_in')) {
          $signed_in = array_get($requestData, 'signed_in');
          return $competition->teams()->withPivot('signed_in')->where('signed_in', $signed_in)->get();
        }
        // No Filtering, return all registered teams
        return $competition->teams()->get();
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
