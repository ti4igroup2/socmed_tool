<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMaster extends Model
{
    //
    protected $table = "group_master";
    public $timestamps = false;
    protected $fillable = ['group_name','group_type','group_status','group_order'];

    public function master()
    {
        return $this->belongsTo('App\Models\SocmedMaster');
    }

    public function cron_email()
    {
        return $this->belongsTo('App\Models\CronEmail');
    }


    public function alexa_master()
    {
        return $this->belongsTo('App\Models\AlexaMaster');

    }



}


