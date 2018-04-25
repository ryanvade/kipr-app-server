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

namespace KIPR\Judging;

use KIPR\Team;
use KIPR\Match;
use KIPR\Competition;
use Illuminate\Contracts\Support\Jsonable;

class Score implements Jsonable
{
    private $team_A = null;
    private $team_A_score = 0;
    private $team_B = null;
    private $team_B_score = 0;
    private $match = null;

    public function __construct()
    {
    }

    public function toJson($options = 0)
    {
        $json = [];
        if ($this->team_A) {
            $this->team_A->score = $this->team_A_score;
            $json["team_A"] = $this->team_A->toJson();
        }

        if ($this->team_B) {
            $this->team_B->score = $this->team_B_score;
            $json["team_B"] = $this->team_B->toJson();
        }

        if ($this->match) {
            $json["match"] = $this->match->toJson();
        }

        return json_encode($json, $options);
    }
}
