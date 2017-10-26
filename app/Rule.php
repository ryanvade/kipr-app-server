<?php

namespace KIPR;

use KIPR\Competition;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $fillable = [
        'year',
        'rules',
        'competition_id'
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function getParsedRules()
    {
        $rules = collect(json_decode($this->rules));
        return $rules;
    }
}
