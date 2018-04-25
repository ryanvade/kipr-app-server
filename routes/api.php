<!-- Copyright (c) 2018 KISS Institute for Practical Robotics

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
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. -->
<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Competitions
Route::post('/competition', 'CompetitionController@create');
Route::get('/competition', 'CompetitionController@getAll');
Route::get('/competition/names', 'CompetitionController@getNames');
Route::get('/competition/count', 'CompetitionController@getCompetitionCount');
Route::get('/competition/current', 'CompetitionController@getCurrentCompetitions');
Route::get('/competition/{competition}', 'CompetitionController@get');
Route::delete('/competition/{competition}', 'CompetitionController@delete');
Route::patch('/competition/{competition}', 'CompetitionController@patch');
Route::get('/competition/{competition}/matches', 'CompetitionController@getMatches');
Route::get('/competition/{competition}/tokens/judging', 'ApiController@getAuthTokensForJudging');
Route::get('/competition/{competition}/tokens/signin', 'ApiController@getAuthTokensForSignIn');
Route::get('/competition/{competition}/team', 'TeamController@getTeamsAtCompetition');
Route::get('/competition/{competition}/team/{team}/seed', 'TeamController@seed');
Route::post('/competition/{competition}/team/{team}/signin', 'TeamController@signin');
Route::post('/competition/{competition}/match/{match}/score', 'MatchController@score');

Route::post('/competition/{competition}/team/register', 'RegistrationController@register');
Route::post('/competition/{competition}/team/deregister', 'RegistrationController@deregister');
Route::get('/competition/{competition}/team/notregistered', 'RegistrationController@teamsNotRegisteredWithATeam');
// Teams
Route::post('/team', 'TeamController@create');
Route::get('/team', 'TeamController@getAll');
Route::post('/team/file', 'TeamController@massUpload');
Route::get('/team/count', 'TeamController@getTeamCount');
Route::get('/team/{team}', 'TeamController@get');
Route::delete('/team/{team}', 'TeamController@delete');
Route::patch('/team/{team}', 'TeamController@patch');
Route::get('/competition/{competition}/tokens/judging', 'ApiController@getAuthTokensForJudging');
Route::get('/competition/{competition}/tokens/signin', 'ApiController@getAuthTokensForSignIn');
Route::post('/competition/{competition}/team/{team}/signin', 'TeamController@signin');
Route::get('/competition/{competition}/schedule', 'ScheduleController@getSchedule');
Route::post('/competition/{competition}/updateSchedule', 'ScheduleController@updateSchedule');

// Matches
Route::get('/match', 'MatchController@getAll');
Route::get('/match/count', 'MatchController@getMatchCount');
Route::get('/match/{match}', 'MatchController@get');

// Rulesets
Route::post('/ruleset', 'RulesetController@create');
Route::get('/ruleset/{ruleset}', 'RulesetController@get');

// Extra Auth
Route::get('/auth/token', 'ApiController@getToken');

// Documents
Route::apiResource('/document', 'CompetitionDocumentController');
