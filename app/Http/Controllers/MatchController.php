<?php

namespace KIPR\Http\Controllers;

use Illuminate\Http\Request;
use KIPR\Judging\Tabulator;

class MatchController extends Controller
{
    public function score(Request $request) {
        if($request->has("results")) {
            $json = $request->input("results");
            $results = json_decode($json, true);

			// TODO Get rules to score match by
            $rules = json_decode("{}");
            $results = Tabulator::scoreMatch($rules, $results);

            return response()->json($results);
        }
        abort(400, "Missing results field");
    }
}

