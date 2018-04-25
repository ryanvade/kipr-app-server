<!-- Copyright (c) 2018 KISS Institute for Practical Robotics

BSD v3 License

All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this
  list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright notice,
  this list of conditions and the following disclaimer in the documentation
  and/or other materials provided with the distribution.

* Neither the name of KIPR Scoring App nor the names of its
  contributors may be used to endorse or promote products derived from
  this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. -->
<?php

namespace KIPR;

use KIPR\Team;
use KIPR\Rule;
use KIPR\Match;
use KIPR\CompetitionTeam;
use KIPR\CompetitionDocument;
use KIPR\Events\CompetitionCreated;
use KIPR\Scheduling\DoubleElim;
use KIPR\Scheduling\Seeding;
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
