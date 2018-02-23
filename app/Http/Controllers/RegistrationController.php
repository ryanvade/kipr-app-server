<?php

namespace KIPR\Http\Controllers;

use KIPR\Team;
use KIPR\Competition;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function register(Competition $competition, Request $request)
    {
        $request->validate([
          'team_ids' => 'required|array|exists:teams,id'
        ]);
        $competition->teams()->syncWithoutDetaching($request->get('team_ids'));
        return response()->json([
          'teams' => $competition->teams()->get()
        ]);
    }

    public function deregister(Competition $competition, Request $request)
    {
      $request->validate([
        'team_ids' => 'required|array|exists:teams,id'
      ]);
      $competition->teams()->detach($request->get('team_ids'));
      return response()->json([
        'teams' => $competition->teams()->get()
      ]);
    }
}
