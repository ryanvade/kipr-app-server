<?php

namespace KIPR\Http\Controllers;

use KIPR\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function getTeamCount() {
      $count = Team::count();
      return response()->json([
        'status' => 'success',
        'team_count' => $count
      ]);
    }
}
