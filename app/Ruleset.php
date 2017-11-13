<?php

namespace KIPR;

use KIPR\Competition;
use Illuminate\Database\Eloquent\Model;

class Ruleset extends Model
{
    protected $fillable = [
        'events',
        'rules',
    ];
    protected $casts = [
        'events' => 'object',
        'rules' => 'object'
    ];
}
