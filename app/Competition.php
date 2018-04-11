<?php

namespace KIPR;

use KIPR\Team;
use KIPR\Rule;
use KIPR\Match;
use KIPR\CompetitionTeam;
use KIPR\CompetitionDocument;
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

    public function documents() {
      return $this->belongsToMany(CompetitionDocument::class, 'competition_competition_document', 'document_id', 'competition_id');
    }
}
