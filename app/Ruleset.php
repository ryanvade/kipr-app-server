<?php

namespace KIPR;

use KIPR\Competition;
use KIPR\Events\RulesetCreated;
use Illuminate\Database\Eloquent\Model;

class Ruleset extends Model
{
    protected $fillable = [
        'name',
        'events',
        'rules',
    ];
    protected $casts = [
        'events' => 'object',
        'rules' => 'object'
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => RulesetCreated::class,
    ];

}
