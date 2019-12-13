<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocmedAllowedUsers extends Model
{
    //
    protected $table = "allowed_users";
    public $timestamps = true;
    protected $guarded = [] ;
    protected $fillable = ['user_email','role'];

}


