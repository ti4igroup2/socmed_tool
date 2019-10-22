<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SocmedMaster;
use App\Models\AlexaMaster;
use DB;
class AlexaReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($id)
    {
        
        $data["summary"] = $this->growth_summary($id);
        $data["alexa"] = $this->alexa_detail($id);
        return View("report.reportAlexa",$data);
    }

    private function growth_summary($id){
       $now = AlexaMaster::with(["withAlexa" => function($q){
            $q->select(['alexa_id','alexa_rank','alexa_local_rank'])->whereraw('cast(created_at as date)=cast(now() as date)');
        }])->where('id',$id)->get();
      $yesterday = AlexaMaster::with(["withAlexa" => function($q){
        $q->select(['alexa_id','alexa_rank','alexa_local_rank'])->whereraw('cast(created_at as date)=cast(now() - INTERVAL 1 DAY as date)');
        }])->where('id',$id)->get();


        if(!empty($now[0]["withAlexa"][0]["alexa_rank"]) && !empty($yesterday[0]["withAlexa"][0]["alexa_rank"])){
            $today_rank = $now[0]["withAlexa"][0]["alexa_rank"] - $yesterday[0]["withAlexa"][0]["alexa_rank"];
            $today_local_rank = $now[0]["withAlexa"][0]["alexa_local_rank"] - $yesterday[0]["withAlexa"][0]["alexa_local_rank"];
            $today_rank_percent = ($today_rank/$yesterday[0]["withAlexa"][0]["alexa_rank"])*100;
            $today_local_rank_percent = ($today_local_rank/$yesterday[0]["withAlexa"][0]["alexa_local_rank"])*100;
        }else{
            $today_rank = 0;
            $today_local_rank = 0;
            $today_rank_percent = 0;
            $today_local_rank_percent = 0;
        }
  
        $data = ["rank"=>rupiah(switchNumber($today_rank)),"rank_percent"=>round(switchNumber($today_rank_percent),3),"local_rank"=>rupiah(switchNumber($today_local_rank)),"local_rank_percent"=>round(switchNumber($today_local_rank_percent),3)];
        return $data;
    }

    private function alexa_detail($id){
        return AlexaMaster::with("lastCounts","groupMaster")->where("id",$id)->first();
    }



    // AJAX REQUEST
    
    public function getFilterReport($id,$action,Request $r){
        $monday = strtotime("last monday");
        $monday = date('w', $monday)==date('w') ? $monday+7*86400 : $monday;
        $end = date("Y-m-d");
        if($action=="month"){
            $this_sd = date('Y-m-01',strtotime('this month'));
        }else if($action=="week"){
            $this_sd =  date('Y-m-d', strtotime('-7 days'));
        }else if($action == "range"){
            $start = $r->start;
            $this_sd = date("Y-m-d",strtotime($start));
            $end = date("Y-m-d",strtotime($r->end));
        }else{
            $this_sd = date("Y-m-d");
        }
        $result = AlexaMaster::with(["withAlexa" => function($q) use($this_sd,$end){
            $q->select(['alexa_id','alexa_rank','alexa_local_rank'])->selectraw('cast(created_at as date) as tgl')->whereraw('cast(created_at as date) >= cast("'.$this_sd.'" as date) and cast(created_at as date) <= cast("'.$end.'"  as date)');
        }])->where('id',$id)->get();
        for($i=0;$i<count($result[0]->withAlexa);$i++){
        $isi = (($result[0]->withAlexa[$i]["alexa_rank"]=="")?0:$result[0]->withAlexa[$i]["alexa_rank"]);
        $isi2 = (($result[0]->withAlexa[$i]["alexa_local_rank"]=="")?0:$result[0]->withAlexa[$i]["alexa_local_rank"]);
        $tgl = (($result[0]->withAlexa[$i]["tgl"]=="")?"not analyzing":$result[0]->withAlexa[$i]["tgl"]);
        $data[] = ["tgl"=>date("d M Y",strtotime($tgl)),"total"=>$isi,"total2"=>$isi2];
        }
        json_true($data);
    }

}
