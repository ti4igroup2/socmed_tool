<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fanspage extends Model
{
    //
    protected $table = "fanspage";
    public $timestamps = true;
    protected $fillable = ['user_id','fanpage_id','fanpage_name','access_token','fanpage_status'];
    public function post()
    {
        return $this->belongsTo('App\Models\Post');

    }

}


