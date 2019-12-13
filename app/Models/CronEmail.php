<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CronEmail extends Model
{
    //
    protected $table = "cron_email";
    public $timestamps = false;
    protected $fillable = ['email_subject','email_recipient','email_cron','email_grouplist'];

}


