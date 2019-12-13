<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocmedLastRetrieved extends Model
{
    //
    protected $table = "last_retrieved";
    public $timestamps = true;
    protected $fillable = [
        'socmed_id',
        'socmed_total',
      ];

      public function masters()
      {
          return $this->belongsTo('App\Models\SocmedMaster')->orderBy('socmed_total','desc');
  
      }
    public function master()
    {
        return $this->belongsToMany('App\Models\SocmedMaster');

    }
}
