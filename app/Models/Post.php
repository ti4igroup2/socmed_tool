<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    protected $table = "post";
    public $timestamps = true;
    protected $fillable = ['id','fanpage_id','message','full_picture','created_time','admin_creator','shares','likes','comments','love','wow','haha','sad','angry','after'];

    public function fanpage()
    {
        return $this->hasOne('App\Models\Fanspage','fanpage_id','fanpage_id');
    }
}


