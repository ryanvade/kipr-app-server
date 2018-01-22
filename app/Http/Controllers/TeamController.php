<?php

namespace KIPR\Http\Controllers;

use KIPR\Team;
use Illuminate\Http\Request;
use KIPR\Http\Requests\CreateTeam;
use KIPR\Http\Requests\UpdateTeam;

class TeamController extends Controller
{
    public function getTeamCount() {
      $count = Team::count();
      return response()->json([
        'status' => 'success',
        'team_count' => $count
      ]);
    }

    public function create(CreateTeam $request) {
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

    public function getAll() {
      return Team::paginate(20);
    }

    public function get(Team $team) {
      return $team;
    }

    public function delete(Team $team) {
      $team->delete();
      return response()->json([
        'status' => 'success',
        'message' => $team->id . ' deleted'
      ]);
    }

    public function patch(Team $team, UpdateTeam $request) {
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
}
