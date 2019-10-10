<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SocmedMaster;
use DB;
class ReportController extends Controller
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
        $data["sosmed"] = $this->sosmed_detail($id);
        return View("report.report",$data);
    }

    private function growth_summary($id){
       $now = SocmedMaster::with(["withSocmedRetrieved" => function($q){
            $q->select(['socmed_id','socmed_total'])->whereraw('cast(created_at as date)=cast(now() as date)');
        }])->where('id',$id)->get();
      $yesterday = SocmedMaster::with(["withSocmedRetrieved" => function($q){
        $q->select(['socmed_id','socmed_total'])->whereraw('cast(created_at as date)=cast(NOW() - INTERVAL 1 DAY as date)');
        }])->where('id',$id)->get();

        
            if(!empty($now[0]["withSocmedRetrieved"][0]["socmed_total"]) && !empty($yesterday[0]["withSocmedRetrieved"][0]["socmed_total"])){
                $today_count = $now[0]["withSocmedRetrieved"][0]["socmed_total"] - $yesterday[0]["withSocmedRetrieved"][0]["socmed_total"];
                $today_percent = ($today_count/$yesterday[0]["withSocmedRetrieved"][0]["socmed_total"])*100;
            }else{
                $today_count = 0;
                $today_percent = 0;
            }
  
        $data = ["count"=>rupiah($today_count),"percent"=>round($today_percent,3)];
        return $data;
    }

    private function sosmed_detail($id){
        return SocmedMaster::with("lastCounts","groupMaster")->where("id",$id)->first();
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
        $result = SocmedMaster::with(["withSocmedRetrieved" => function($q) use($this_sd,$end){
            $q->select(['socmed_id','socmed_total'])->selectraw('cast(created_at as date) as tgl')->whereraw('cast(created_at as date) >= cast("'.$this_sd.'" as date) and cast(created_at as date) <= cast("'.$end.'"  as date)');
        }])->where('id',$id)->get();
        for($i=0;$i<count($result[0]->withSocmedRetrieved);$i++){
        $isi = (($result[0]->withSocmedRetrieved[$i]["socmed_total"]=="")?0:$result[0]->withSocmedRetrieved[$i]["socmed_total"]);
        $tgl = (($result[0]->withSocmedRetrieved[$i]["tgl"]=="")?"not analyzing":$result[0]->withSocmedRetrieved[$i]["tgl"]);
        $data[] = ["tgl"=>date("d M Y",strtotime($tgl)),"total"=>$isi];
        }
        json_true($data);
    }

}
