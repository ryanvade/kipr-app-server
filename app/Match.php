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
use KIPR\Competition;
use KIPR\Events\MatchCreated;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $fillable = [
      'match_type',
      'round',
      'match_time',
      'match_table',
      'competition_id',
      'results',
      'winner',
      'team_A',
      'team_B',
      'match_A',
      'match_B',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => MatchCreated::class,
    ];

    protected $with = [
      'teamA',
      'teamB',
      'competition'
    ];

    public function teamA()
    {
        return $this->belongsTo(Team::class, 'team_A');
    }

    public function teamB()
    {
        return $this->belongsTo(Team::class, 'team_B');
    }

    public function winner()
    {
        return $this->belongsTo(Team::class, 'winner');
    }

    public function matchA()
    {
        return $this->belongsTo(Match::class, 'match_A');
    }

    public function matchB()
    {
        return $this->belongsTo(Match::class, 'match_B');
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function setFinalScore(Score $score)
    {
        $this->results = score;
    }

    public function setResults($results)
    {
        if (is_array($results)) {
            $results = json_encode($results);
        }
        $this->update([
        'results' => $results
      ]);
        # TODO: Fire Event for match end
    }
}
