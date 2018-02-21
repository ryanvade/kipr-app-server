<?php

namespace KIPR\Http\Controllers;

use KIPR\Team;
use KIPR\Competition;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function __construct() {
      $this->middleware('auth:api');
    }

    public function register(Competition $competition, Team $team, Request $request) {
      $competition->teams()->attach($team);
      return response()->json([
        'status' => 'success',
        'message' => 'team registered'
      ]);
    }

    public function deregister(Competition $competition, Team $team, Request $request) {
      $competition->teams()->detach($team);
      return response()->json([
        'status' => 'success',
        'message' => 'team deregistered'
      ]);
    }

    public function teamsNotRegisteredWithATeam(Competition $competition) {
      return Team::whereNotIn('id', function($query) use($competition) {
        $query->select('team_id')->from('competition_teams')->where('competition_id', $competition->id);
      })->get();
    }
}
