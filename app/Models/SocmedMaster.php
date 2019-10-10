<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SocmedLastRetrieved;

class SocmedMaster extends Model
{
    //
    protected $table = "master";
    public $timestamps = true;
    protected $fillable = ['socmed_name','socmed_type','socmed_url','socmed_status','group_id'];

    public function lastCounts()
    {
        return $this->hasOne('App\Models\SocmedLastRetrieved','socmed_id','id')->latest();

         // return \App\Article::where('type_id', 4)
         //    ->where('published_at', '<=', $this->published_at)
         //    ->where('show', 1)
         //    ->where('id', '<>', $this->id)
         //    ->orderBy('published_at', 'desc');
    }
    
    public function groupMaster()
    {
        return $this->hasOne('App\Models\GroupMaster','id','group_id');
    }

    public function groupMasters()
    {
        return $this->hasMany('App\Models\GroupMaster','id','group_id');
    }

    public function withSocmedRetrieved()
    {
        return $this->hasMany('App\Models\SocmedLastRetrieved','socmed_id','id');

    }

}


