<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SocmedMaster;
use App\Models\SocmedLastRetrieved;
use DB,Cookie;

class SocmedMasterController extends Controller
{
    //

    function index()
    {
       return View("dashboard.sosmed");
    }

 
    public function action(Request $r)
    {
        $validate = $r->validate([
            'socmed_name' => 'required',
            'socmed_type' => 'required',
            'socmed_url' => 'required',
            'socmed_groupid' => 'required',
            'socmed_status' => 'required'
        ]);
        
        
        $data = [
            'socmed_name' =>  $r->socmed_name,
            'socmed_type' => $r->socmed_type,
            'socmed_url' => $r->socmed_url,
            'group_id' => $r->socmed_groupid,
            'socmed_status' => $r->socmed_status
        ];
   
        if($r->action=="add")
        {
            SocmedMaster::create($data);
        
        }else if($r->action=="update"){
        
            SocmedMaster::where('id',$r->id)->update($data);
        }else{
        
            print json_encode("error");
        }
        
        
       print json_encode("success");
    }

    public function detail_socmed(Request $r)
    {
        $validate = $r->validate([
            'id' => 'required'
        ]);
        
        
        $response = SocmedMaster::where('id',$r->id)->get();
        json_true($response);
    }

    public function delete($id)
    {
        SocmedMaster::find($id)->delete();
        print json_encode("success");
    }


    public function count_sosmed(Request $r)
    {
        if($r->status == ""){
            $operat = "!=";
        }else{
            $operat = "=";
        }
        
        if($r->group==""){$operat2 = "!="; }else{$operat2 = "=";}
        
        $count = SocmedMaster::where('socmed_status',$operat,$r->status)
        ->where('group_id',$operat2,$r->group)->where(function($query) use ($r){
            $query->where('socmed_name','LIKE','%'.$r->search.'%')
            ->orwhere('socmed_type','LIKE','%'.$r->search.'%');
        })->count();
        
        json_true($count);
        
    }

    public function get_sosmed(Request $r)
    {
        if($r->status == ""){
        
            $operat = "!=";
        }else{
            $operat = "=";
        }
        if($r->group==""){$operat2 = "!="; }else{$operat2 = "=";}

        if($r->orderByCol!="socmed_total")
        {
            
            $sosmed = SocmedMaster::where('socmed_status',$operat,$r->status)
            ->where('group_id',$operat2,$r->group)
            ->where(function($query) use ($r){
                $query->where('socmed_name','LIKE','%'.$r->search.'%')
                ->orwhere('socmed_type','LIKE','%'.$r->search.'%');
            })
            ->with('lastCounts','groupMaster')
            ->skip($r->offset)
            ->limit($r->limit)
            ->orderByRaw($r->orderByCol." ".$r->orderByAct)
            ->get();
       
        }else {
        
            $last = SocmedLastRetrieved::orderby($r->orderByCol, $r->orderByAct)
            ->orderByRaw('date(created_at) desc')
            ->distinct()->get(['socmed_id','socmed_total','created_at'])
            ->pluck('socmed_id')
            ->toArray();
          
            $order_array = implode(",",$last);
            $sosmed = SocmedMaster::whereIn('id',$last)
            ->where('socmed_status',$operat,$r->status)
            ->where('group_id',$operat2,$r->group)
            ->where(function($query) use ($r){
                $query->where('socmed_name','LIKE','%'.$r->search.'%')
                ->orwhere('socmed_type','LIKE','%'.$r->search.'%');
            })
            ->with('lastCounts','groupMaster')
            ->skip($r->offset)
            ->limit($r->limit)
            ->orderByRaw('FIELD(id,'.$order_array.')')
            ->get();
        }

        for($i=0;$i<count($sosmed);$i++)
        {
            $sosmed[$i]["channelid"] = UrlToUsername($sosmed[$i]["socmed_url"],$sosmed[$i]["socmed_type"]);
        }
        
        json_true($sosmed);
    }

}
