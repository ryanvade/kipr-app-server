<?php

namespace KIPR\Scheduling;

use KIPR\Team;
use KIPR\Match;

abstract class Bracket {
    abstract public function scheduleMatches($matches);
    abstract public function createMatches($competition, $teams);
}

