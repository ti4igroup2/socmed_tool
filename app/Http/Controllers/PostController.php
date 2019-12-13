<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \GuzzleHttp\Client;
use App\Models\Fanspage;
use App\Models\Post;
use Config,Auth,Artisan,Exception;


class PostController extends Controller
{
    protected $u_id;
   

    function index()
    {
        return View("fanspage.post");
    }

   
    public function count_post(Request $r)
    {
        $count = Post::where(function($query) use ($r)
            {
                $query->where('message','LIKE','%'.$r->search.'%')
                ->orwhere('admin_creator','LIKE','%'.$r->search.'%');
                
            })->when($r->fanspage_search!="",function($q) use($r){
            
                return $q->where("fanpage_id",$r->fanspage_search);
                
            })->when($r->creatorSearch!="",function($q) use($r){
            
                return $q->where("admin_creator",$r->creatorSearch);
            })
        ->where('created_time','LIKE','%'.$r->dateSearch.'%')
        ->count();
        
        json_true($count);
    }
    public function get_post(Request $r)
    {
    
    
        $sosmed = Post::where(function($query) use ($r)
        {
            $query->where('message','LIKE','%'.$r->search.'%')
            ->orwhere('admin_creator','LIKE','%'.$r->search.'%');
            
        })->when($r->fanspage_search!="",function($q) use($r){
        
            return $q->where("fanpage_id",$r->fanspage_search);
            
        })->when($r->creatorSearch!="",function($q) use($r){
        
            return $q->where("admin_creator",$r->creatorSearch);
        })
        ->where('created_time','LIKE','%'.$r->dateSearch.'%')
        ->skip($r->offset)
        ->limit($r->limit)
        ->orderByRaw($r->orderByCol." ".$r->orderByAct)
        ->get();
        
        for($i=0;$i<count($sosmed);$i++)
        {
            $sosmed[$i]["datepost"] = date('d M Y H:i',strtotime($sosmed[$i]->created_time));
        }
        
        json_true($sosmed);
    }

    public function get_fanspage(Request $r)
    {
        $sosmed = Fanspage::select('fanpage_id','fanpage_name')->where('fanpage_status',"1")->get();
        json_true($sosmed);
    }

    public function get_creator(Request $r)
    {   
        $creator = Post::select('admin_creator')->where('admin_creator',"!=","Admin")->distinct()->get();
        json_true($creator);
    }



    

}