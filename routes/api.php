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
Route::get('/user', 'UserController@current');
Route::get('/match/count', 'MatchController@getMatchCount');
// Competitions
Route::post('/competition', 'CompetitionController@create');
Route::get('/competition', 'CompetitionController@getAll');
Route::get('/competition/names', 'CompetitionController@getNames');
Route::get('/competition/count', 'CompetitionController@getCompetitionCount');
Route::get('/competition/current', 'CompetitionController@getCurrentCompetitions');
// Teams
Route::post('/team', 'TeamController@create');
Route::get('/team', 'TeamController@getAll');
Route::get('/team/count', 'TeamController@getTeamCount');

Route::patch('/match/{match}/score', 'MatchController@update');
Route::get('/competition/{competition}', 'CompetitionController@get');
Route::delete('/competition/{competition}', 'CompetitionController@delete');
Route::get('/team/{team}', 'TeamController@get');
Route::delete('/team/{team}', 'TeamController@delete');
Route::get('/competition/{competition}/tokens/judging', 'ApiController@getAuthTokensForJudging');
Route::get('/competition/{competition}/tokens/signin', 'ApiController@getAuthTokensForSignIn');
