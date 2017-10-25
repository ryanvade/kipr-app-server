<?php

namespace KIPR;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $fillable = [
        'year',
        'rules'
    ];

    public function getParsedRules()
    {
      return collect(json_decode($this->rules));
    }
}
