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
// Matches
Route::get('/match/count', 'MatchController@getMatchCount');
// Competitions
Route::post('/competition', 'CompetitionController@create');
Route::get('/competition', 'CompetitionController@getAll');
Route::get('/competition/names', 'CompetitionController@getNames');
Route::get('/competition/count', 'CompetitionController@getCompetitionCount');
Route::get('/competition/current', 'CompetitionController@getCurrentCompetitions');
Route::get('/competition/{competition}', 'CompetitionController@get');
Route::delete('/competition/{competition}', 'CompetitionController@delete');
Route::patch('/competition/{competition}', 'CompetitionController@patch');
Route::get('/competition/{competition}/tokens/judging', 'ApiController@getAuthTokensForJudging');
Route::get('/competition/{competition}/tokens/signin', 'ApiController@getAuthTokensForSignIn');
Route::get('/competition/{competition}/team', 'TeamController@getTeamsAtCompetition');
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
// Extra Auth
Route::get('/auth/token', 'ApiController@getToken');
