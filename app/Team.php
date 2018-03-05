<?php

namespace KIPR;

use KIPR\Match;
use KIPR\Competition;
use KIPR\Events\TeamCreated;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'email', 'code'];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
    ];

    public function competitions()
    {
      return $this->belongsToMany(Competition::class, 'competition_teams');
    }

    public function matches()
    {
      return $this->belongsToMany(Match::class);
    }
}
