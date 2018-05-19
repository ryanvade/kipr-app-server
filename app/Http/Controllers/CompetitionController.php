<?php
/*
 Copyright (c) 2018 KISS Institute for Practical Robotics

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
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
namespace KIPR\Http\Controllers;

use Validator;
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
    $this->middleware('auth:api', [
      'only' => [
        'delete',
        'create',
        'patch',

      ]
    ]);
  }

    public function index(Request $request) {
      return Competition::orderBy('start_date', 'DESC')->paginate(20);
    }

    public function orderByYear(Request $request, $year) {
      $start = new Carbon('first day of January ' . $year);
      $end = new Carbon('last day of December ' . $year);
      return Competition::where('start_date', '>=', $start)
                          ->where('start_date', '<=', $end)
                          ->paginate(20);
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

    public function getMatches(Competition $competition) {
        $matches = $competition->matches()->get();
        foreach ($matches as $match) {
            $match->results = json_decode($match->results);
        }
        return $matches;
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
