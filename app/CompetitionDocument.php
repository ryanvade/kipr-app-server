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

    /**
     * Boot - Function that is called when a Model is created
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        // manually fire off deleted events
        static::deleted(function ($document) {
          Storage('public')->delete($document->file_location);
        });
    }

    public function competitions() {
      return $this->belongsToMany(Competition::class, 'competition_competition_document', 'competition_id', 'document_id');
    }
}
