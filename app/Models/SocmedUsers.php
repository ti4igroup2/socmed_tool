<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocmedUsers extends Model
{
    //
    protected $table = "users";
    public $timestamps = true;
    protected $guarded = [] ;
    protected $fillable = ['user_realname','user_email','user_photo','user_isValid','user_role','remeber_token'];

}


