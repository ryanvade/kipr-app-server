<?php

namespace KIPR;

use KIPR\Team;
use KIPR\Rule;
use KIPR\Match;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{

    protected $fillable = [
      'name',
      'location',
      'start_date',
      'end_date'
    ];

    public function teams()
    {
      return $this->belongsToMany(Team::class, 'competition_teams');
    }

    public function matches()
    {
      return $this->hasMany(Match::class);
    }

    public function rules()
    {
      return $this->hasMany(Rule::class);
    }
}
