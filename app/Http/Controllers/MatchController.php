<?php

namespace KIPR\Http\Controllers;

use Illuminate\Http\Request;
use KIPR\Judging\Tabulator;
use KIPR\Ruleset;

class MatchController extends Controller
{
    public function score(Request $request) {
        if(!$request->has("results"))
            abort(400, "Missing results field");

        if(!$request->has("ruleset_id"))
            abort(400, "Missing ruleset_id field");

        $json = $request->input("results");
        $results = json_decode($json, true);

        $ruleset = Ruleset::findOrFail($request->input("ruleset_id"));
        $results = Tabulator::scoreMatch($ruleset, $results);

        return response()->json($results);
    }
}

