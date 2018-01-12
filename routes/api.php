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
Route::get('/team/count', 'TeamController@getTeamCount');
Route::get('/match/count', 'MatchController@getMatchCount');
Route::get('/competition/count', 'CompetitionController@getCompetitionCount');
Route::get('/competition/current', 'CompetitionController@getCurrentCompetitions');


Route::patch('/match/{match}/score', 'MatchController@update');
Route::get('/competition/{competition}/tokens/judging', 'ApiController@getAuthTokensForCompetition');
