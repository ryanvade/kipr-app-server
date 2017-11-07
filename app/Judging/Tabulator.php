<?php

namespace KIPR\Judging;

use KIPR\Team;
use KIPR\Match;
use KIPR\Score;
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

    private static function _score($rules, $score)
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

    // Take match
    // Return score
    public static function score($rule, $match)
    {
        if ($rule == null) {
            $rule = $match->competition->rule;
        }

        // Initial score registers
        $score = Tabulator::parse($match, true);

        // List of rules to apply in order
        $rules = Tabulator::parse($rule);

        // Validate the match
        foreach (array_keys($score) as $event) {
            if (!array_key_exists($event, $rules->events)) {
                throw new InvalidResultException("Unknown event: $event\n");
            }
        }

        // Score the match
        Tabulator::_score($rules->rules, $score);

        $sum = 0;
        foreach ($score as $catagory) {
            $sum += $catagory;
        }
        $score["total"] = $sum;
        return $score;
    }

    public static function parse($json, $array = false)
    {
        $results = json_decode($json, $array);

        //foreach ($results->keys() as $key) {
        //$results[$key] = collect($results[$key]);
        //}
        return $results;
    }
}
