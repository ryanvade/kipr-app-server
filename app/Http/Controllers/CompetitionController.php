<?php

namespace KIPR\Http\Controllers;

use KIPR\Competition;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    /**
     * Teams - Get the teams registered to a Competition
     * @param  Competition $competition Competition to get teams for
     * @return JSON                     All teams of the competition
     */
    public function teams(Competition $competition)
    {
        return $competition->teams()->get();
    }
}
