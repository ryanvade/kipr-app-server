<?php

namespace KIPR;

use KIPR\Team;
use KIPR\Rule;
use KIPR\Match;
use KIPR\CompetitionTeam;
use KIPR\Events\CompetitionCreated;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    protected $fillable = [
      'name',
      'location',
      'start_date',
      'end_date'
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => CompetitionCreated::class,
    ];

    protected $with = [
      'ruleset'
    ];

    public function generateMatches()
    {
        // Generate 3 seeding matches for all teams
        $teams = $this->teams()->get();
        foreach ($teams as $team) {
            for($n = 0; $n < 3; $n++) {
                $this->matches()->create([
                    'team_A' => $team->id,
                    'match_type' => "seeding $n"
                ]);
            }
        }
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'competition_teams')->using(CompetitionTeam::class);
    }

    public function matches()
    {
        return $this->hasMany(Match::class);
    }

    public function ruleset()
    {
        return $this->belongsTo(Ruleset::class);
    }

    public function setRuleset(Ruleset $ruleset) {
      $this->ruleset()->associate($ruleset);
      return $this;
    }

    public function competitionTeams() {
      return $this->hasMany(CompetitionTeam::class);
    }
}
