<?php

namespace KIPR\Http\Controllers;

use Carbon\Carbon;
use KIPR\Competition;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    public function getCurrentCompetitions()
    {
        $today = Carbon::now();
        $competitions = Competition::where('start_date', '>', $today)
                                  ->where('end_date', '<', $today)
                                  ->get();
        return response()->json([
          'status' => 'success',
          'competitions' => $competitions
        ]);
    }
}
