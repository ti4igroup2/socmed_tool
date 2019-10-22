<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AlexaMaster;

class AlexaLastRetrieved extends Model
{
    //
    protected $table = "alexa_last_retrieved";
    public $timestamps = true;
    protected $fillable = ['alexa_id','alexa_rank','alexa_local_rank'];

    public function master()
    {
        return $this->belongsToMany('App\Models\AlexaMaster');
    }


}


