<?php

namespace KIPR;

use KIPR\Team;
use KIPR\Competition;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CompetitionTeam extends Pivot
{
    protected $table = 'competition_teams';
    protected $fillable = ['signed_in', 'sign_in_time'];
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
