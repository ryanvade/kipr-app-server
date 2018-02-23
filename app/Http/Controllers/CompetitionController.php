<?php

namespace KIPR\Http\Controllers;

use Carbon\Carbon;
use KIPR\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use KIPR\Filters\CompetitionFilter;
use KIPR\Http\Requests\CreateCompetition;
use KIPR\Http\Requests\UpdateCompetition;

class CompetitionController extends Controller
{

  public function __construct() {
    $this->middleware('auth', [
      'except' => [
        'getAll',
        'getCurrentCompetitions',
        'getCompetitionCount',
        'get',
        'getNames'
      ]
    ]);
  }
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

    public function getAll(Request $request) {
      $filter = new CompetitionFilter($request);
      $teams = $filter->apply(DB::table('competitions'));
      return $teams->paginate(20);
    }

    public function getCompetitionCount()
    {
        $count = Competition::count();
        return response()->json([
        'status' => 'success',
        'competition_count' => $count
      ]);
    }

    public function get(Competition $competition) {
      $competition->teams = $competition->teams()->get();
      return $competition;
    }

    public function delete(Competition $competition) {
      $competition->delete();
      return response()->json([
        'status' => 'success',
        'message' => $competition->id . ' deleted'
      ]);
    }

    public function create(CreateCompetition $request)
    {
        $comp = Competition::create([
        'name' => $request->name,
        'location' => $request->location,
        'start_date' => Carbon::createFromFormat("m/d/Y h:iA", $request->startDate),
        'end_date' => Carbon::createFromFormat("m/d/Y h:iA", $request->endDate)
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

    public function patch(Competition $competition, UpdateCompetition $request) {
      $competition->update([
        'name' => $request->name,
        'location' => $request->location,
        'start_date' => Carbon::createFromFormat("m/d/Y h:mA", $request->startDate),
        'end_date' => Carbon::createFromFormat("m/d/Y h:mA", $request->endDate)
      ]);
      return response()->json([
        'status' => 'success',
        'competition' => $competition
      ]);
    }
}
