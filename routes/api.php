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
Route::patch('/match/{match}/score', 'MatchController@update');
Route::get('/competition/{competition}/teams', 'CompetitionController@teams');
Route::post('/competition/{competition}/team/{team}/signin', 'TeamController@signin');
