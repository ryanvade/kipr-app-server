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
