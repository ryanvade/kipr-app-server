<?php

namespace KIPR;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $fillable = [
        'year',
        'rules'
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
}
