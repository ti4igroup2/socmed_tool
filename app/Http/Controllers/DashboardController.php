<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SocmedMaster;
use App\Models\SocmedLastRetrieved;
use App\Models\AlexaMaster;
use App\Models\Post;
use App\Models\AlexaLastRetrieved;
use DB,Cookie;

class DashboardController extends Controller
{
    function index()
    {
        return View("dashboard.dashboard");
     }
     
     

     public function getPopularPost()
     {
         $post = Post::whereYear('created_time',"=",date('Y'))
         ->whereMonth("created_time","=",date('m'))
         ->with('fanpage')
         ->get();
         
         $max=[""];
         $result=[''];
         
             foreach($post as $index => $p)
             {
                $sum = (int)$p->shares + (int)$p->likes + (int)$p->comments + (int)$p->love + (int)$p->wow + (int)$p->haha + (int)$p->sad + (int)$p->angry;
                $max[$index] = $sum;
             }
             
             arsort($max);
             $i=0;
             foreach($max as $key=>$val)
             {
                 $result[$i] = $post[$key];
                 if($i==5){ break; }
                 
                $i++;
             }
         json_true($result);
     }

     public function getPopularCreator()
     {
        // 
        $result=[''];
        
        $creator = Post::selectRaw('count(admin_creator) as total,admin_creator')
        ->where('admin_creator','!=',"Admin")
        ->whereYear('created_time',"=",date('Y'))
        ->whereMonth("created_time","=",date('m'))
        ->groupBy('admin_creator')
        ->get()
        ->toArray();
        
        
        arsort($creator);
        $i=0;
        
        foreach($creator as $key=>$val)
        {
            $result[$i] = $creator[$key];
            if($i==4){ break; }
           $i++;
        }
        
        json_true($result);
     }

     public function getRank($id)
     {
        $last = SocmedLastRetrieved::orderby("socmed_total", "desc")->orderBy('created_at',"desc")
        ->distinct()
        ->get(['socmed_id','socmed_total','created_at'])
        ->pluck('socmed_id')
        ->toArray();
        
        
        $order_array = implode(",",$last);
            $sosmed = SocmedMaster::whereIn('id',$last)->
            where('socmed_status',"=","1")->where('group_id',"=",$id)
            ->with('lastCounts')
            ->skip(0)->limit(5)->orderByRaw('FIELD(id,'.$order_array.')')
            ->get();

    
            for($i=0;$i<count($sosmed);$i++)
            {
                $sosmed[$i]["summary"] = $this->sosmed_summary($sosmed[$i]["id"]);
            }
        
        json_true($sosmed);
    }

    public function getAlexaRank($id,$type)
    {
        $last = AlexaLastRetrieved::orderby($type, "asc")
        ->orderBy('created_at',"desc")
        ->distinct()
        ->get(['alexa_id','alexa_rank','alexa_local_rank','created_at'])
        ->pluck('alexa_id')
        ->toArray();
        
        
        $order_array = implode(",",$last);
        
        $sosmed = AlexaMaster::where('group_id',"=",$id)
        ->whereIn('id',$last)
        ->with('lastCounts')
        ->skip(0)
        ->limit(5)
        ->orderByRaw('FIELD(id,'.$order_array.')')
        ->get();

            for($i=0;$i<count($sosmed);$i++)
            {
                $sosmed[$i]["summary"] = $this->alexa_summary($sosmed[$i]["id"],"alexa_local_rank");
                $sosmed[$i]["summary_global"] = $this->alexa_summary($sosmed[$i]["id"],"alexa_rank");
            }
            
        json_true($sosmed);
    }

    

    private function sosmed_summary($id)
    {
        $now = SocmedMaster::with(["withSocmedRetrieved" => function($q){
             $q->select(['socmed_id','socmed_total'])
             ->whereDate('created_at',date('Y-m-d'));
         }])
         ->where('id',$id)
         ->get();
         
       $yesterday = SocmedMaster::with(["withSocmedRetrieved" => function($q){
            $q->select(['socmed_id','socmed_total'])
            ->whereDate('created_at',date('Y-m-d',strtotime('yesterday')));
         }])
         ->where('id',$id)
         ->get();
 
         
             if(!empty($now[0]["withSocmedRetrieved"][0]["socmed_total"]) && !empty($yesterday[0]["withSocmedRetrieved"][0]["socmed_total"]))
             {
                 $today_count = $now[0]["withSocmedRetrieved"][0]["socmed_total"] - $yesterday[0]["withSocmedRetrieved"][0]["socmed_total"];
             }
             else
             {
                 $today_count = 0;
             }
   
            if($today_count>=0)
            {
                $summary = "fas fa-caret-up f-22 m-r-10 text-c-green";
            }
            else
            {
                $summary = "fas fa-caret-down f-22 m-r-10 text-c-red";
            }

        return $summary;
     }

     private function alexa_summary($id,$type)
     {
        $now = AlexaMaster::with(["withAlexa" => function($q){
             $q->select(['alexa_id','alexa_rank','alexa_local_rank'])
             ->whereDate('created_at',date('Y-m-d'));
         }])
         ->where('id',$id)
         ->limit(1)
         ->get();
         
         
       $yesterday = AlexaMaster::with(["withAlexa" => function($q){
             $q->select(['alexa_id','alexa_rank','alexa_local_rank'])
             ->whereDate('created_at',date('Y-m-d',strtotime('yesterday')));
         }])
         ->where('id',$id)
         ->limit(1)
         ->get();
 
 
         if(!empty($now[0]["withAlexa"][0]["alexa_rank"]) && !empty($yesterday[0]["withAlexa"][0]["alexa_rank"]))
         {
             $today_rank = $now[0]["withAlexa"][0]["alexa_rank"] - $yesterday[0]["withAlexa"][0]["alexa_rank"];
             $today_local_rank = $now[0]["withAlexa"][0]["alexa_local_rank"] - $yesterday[0]["withAlexa"][0]["alexa_local_rank"];
         }
         else
         {
             $today_rank = 0;
             $today_local_rank = 0;
         }

         if($type=="alexa_local_rank")
         {
            if($today_local_rank>=0)
            {
                $summary = "fas fa-caret-up f-22 m-r-10 text-c-green";
            }
            else
            {
                $summary = "fas fa-caret-down f-22 m-r-10 text-c-red";
            }
         }
         else
         {
            if($today_rank>=0)
            {
                $summary = "fas fa-caret-up f-22 m-r-10 text-c-green";
            }
            else
            {
                $summary = "fas fa-caret-down f-22 m-r-10 text-c-red";
            }
         }

         return $summary;
   
     }

 
    //
}
