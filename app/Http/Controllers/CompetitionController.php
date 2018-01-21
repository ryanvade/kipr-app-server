<?php

namespace KIPR\Http\Controllers;

use Carbon\Carbon;
use KIPR\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use KIPR\Http\Requests\CreateCompetition;

class CompetitionController extends Controller
{
    public function getCurrentCompetitions()
    {
        $today = Carbon::now();
        $competitions = Competition::where('start_date', '<', $today)
                                  ->where('end_date', '>', $today)
                                  ->get();
        return response()->json([
          'status' => 'success',
          'competitions' => $competitions
        ]);
    }

    public function getCompetitionCount()
    {
        $count = Competition::count();
        return response()->json([
        'status' => 'success',
        'competition_count' => $count
      ]);
    }

    public function create(CreateCompetition $request)
    {
        $comp = Competition::create([
        'name' => $request->name,
        'location' => $request->location,
        'start_date' => Carbon::createFromFormat("m/d/Y h:mA", $request->startDate),
        'end_date' => Carbon::createFromFormat("m/d/Y h:mA", $request->endDate)
      ]);
        return response()->json([
        'status' => 'success',
        'competition' => $comp
      ]);
    }

    public function getNames()
    {
        return DB::table('competitions')->pluck('name');
    }
}
