<?php

namespace KIPR\Http\Controllers;

use DB;
use KIPR\Ruleset;
use Carbon\Carbon;
use Illuminate\Http\Request;
use KIPR\Http\Requests\CreateRuleset;

class RulesetController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth:api', [
        'except' => [
            'get'
        ]
      ]);
    }

    public function create(CreateRuleset $request)
    {
        $ruleset = Ruleset::create([
        'name' => $request->name,
        'events' => $request->events,
        'rules'=> $request->rules
      ]);
        return response()->json([
        'status' => 'success',
        'ruleset' => $ruleset
      ]);
    }

    public function get(Ruleset $ruleset)
    {
        return $ruleset;
    }

    public function delete(Ruleset $ruleset)
    {
        $team->delete();
        return response()->json([
        'status' => 'success',
        'message' => $ruleset->id . ' deleted'
      ]);
    }

    //public function patch(Team $team, UpdateTeam $request)
    //{
        //$team->update([
        //'name' => $request->name,
        //'email' => $request->email,
        //'code' => $request->code
      //]);
        //return response()->json([
        //'status' => 'success',
        //'team' => $team
      //]);
    //}
}
