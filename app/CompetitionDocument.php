<?php

namespace KIPR;

use KIPR\Competition;
use Illuminate\Database\Eloquent\Model;

class CompetitionDocument extends Model
{

    protected $fillable = [
      'name',
      'file_location'
    ];
    
    public function competitions() {
      return $this->belongsToMany(Competition::class, 'competition_competition_document', 'competition_id', 'document_id');
    }
}
