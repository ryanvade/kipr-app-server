<?php
/*
 Copyright (c) 2018 KISS Institute for Practical Robotics

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
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
namespace KIPR\Judging;

use KIPR\Team;
use KIPR\Match;
use KIPR\Score;
use KIPR\Ruleset;
use KIPR\Competition;
use KIPR\Exceptions\InvalidResultException;
use Illuminate\Contracts\Support\Jsonable;

// TODO Make this a singleton?
class Tabulator
{
    public static function evaluateExpression($expr, $score)
    {
        preg_match("/^\s*(\S*)\s*(==|!=)\s*(\S*)\s*$/", $expr, $groups);
        $operator = $groups[2];

        if (is_numeric($groups[1])) {
            $left_operand = intval($groups[1]);
        } else {
            $left_operand = $score[$groups[1]];
        }

        if (is_numeric($groups[3])) {
            $right_operand = intval($groups[3]);
        } else {
            $right_operand = $score[$groups[3]];
        }

        switch ($operator) {
            case "==":
                return $left_operand == $right_operand;
            case "!=":
                return $left_operand != $right_operand;
        }
    }

    private static function _score($rules, &$score)
    {
        foreach ($rules as $r) {
            if ($r->type == "multiplier") {
                $score[$r->target] = ($score[$r->target] ?? 0) * $r->value;
            } elseif ($r->type == "fixed") {
                $score[$r->target] = ($score[$r->target] ?? 0) + $r->value;
            } elseif ($r->type == "set") {
                $score[$r->target] = $r->value;
            } elseif ($r->type == "conditional") {
                if (Tabulator::evaluateExpression($r->value, $score)) {
                    Tabulator::_score($r->target, $score);
                }
            }
        }
    }

    public static function scoreMatch(Ruleset $ruleset, $results) {
        $score_a = Tabulator::score($ruleset, $results["A"]);
        $score_b = Tabulator::score($ruleset, $results["B"]);
        return ["A" => $score_a, "B" => $score_b];
    }

    public static function score(Ruleset $ruleset, $results)
    {
        // Validate the match
        foreach (array_keys($results) as $event) {
            if (!array_key_exists($event, $ruleset->events)) {
                throw new InvalidResultException("Unknown event: $event\n");
            }
        }

        // Score the match
        Tabulator::_score($ruleset->rules, $results);

        $sum = 0;
        foreach ($results as $catagory) {
            $sum += $catagory;
        }
        $results["total"] = $sum;
        return $results;
    }
}
