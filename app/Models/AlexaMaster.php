<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AlexaLastRetrieved;

class AlexaMaster extends Model
{
    //
    protected $table = "alexa_master";
    public $timestamps = true;
    protected $fillable = ['alexa_name','alexa_url','group_id','alexa_group'];

    public function lastCounts()
    {
        return $this->hasOne('App\Models\AlexaLastRetrieved','alexa_id','id')->latest();
    }

    public function groupMaster()
    {
        return $this->hasOne('App\Models\GroupMaster','id','group_id');
    }

    public function withAlexa()
    {
        return $this->hasMany('App\Models\AlexaLastRetrieved','alexa_id','id');

    }



}


