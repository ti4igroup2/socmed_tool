<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AlexaMaster;
use App\Models\AlexaLastRetrieved;


class AlexaMasterController extends Controller
{
    function index(){
       return View("dashboard.alexa");
    }

    // AJAX SERVICE
    public function action(Request $r){
        $validate = $r->validate([
            'alexa_name' => 'required',
            'alexa_url' => 'required',
            // 'alexa_group' => 'required',
        ]);
        $data = [
            'alexa_name' =>  $r->alexa_name,
            'alexa_url' => $r->alexa_url,
            'group_id' => $r->alexa_groupid,
            // 'alexa_group' => $r->alexa_group
        ];
        if($r->action=="add"){
        AlexaMaster::create($data);
        }else if($r->action=="update"){
        AlexaMaster::where('id',$r->id)->update($data);
        }else{
            print json_encode("error");
        }
       print json_encode("success");
    }

    public function detail_alexa(Request $r){
        $validate = $r->validate([
            'id' => 'required'
        ]);
        $response = AlexaMaster::where('id',$r->id)->get();
        json_true($response);
    }

    public function delete($id){
        AlexaMaster::find($id)->delete();
        print json_encode("success");
    }


    public function count_alexa(Request $r){
        if($r->group==""){$operat2 = "!="; }else{$operat2 = "=";}
        $count = AlexaMaster::where('group_id',$operat2,$r->group)->where(function($query) use ($r){
            $query->where('alexa_name','LIKE','%'.$r->search.'%')
            ->orwhere('alexa_group','LIKE','%'.$r->search.'%');
        })->count();
        json_true($count);
        
    }
    public function get_alexa(Request $r){
        if($r->group==""){$operat2 = "!="; }else{$operat2 = "=";}
    if($r->orderByCol == "alexa_rank" || $r->orderByCol == "alexa_local_rank"){

        $last = AlexaLastRetrieved::orderby($r->orderByCol, $r->orderByAct)->orderByRaw('date(created_at) desc')->distinct()->get(['alexa_id','alexa_rank','alexa_local_rank','created_at'])->pluck('alexa_id')->toArray();
        // dd($last);
        $order_array = implode(",",$last);
        $sosmed = AlexaMaster::where('group_id',$operat2,$r->group)->whereIn('id',$last)
            ->where(function($query) use ($r){
            $query->where('alexa_name','LIKE','%'.$r->search.'%')
            ->orwhere('alexa_group','LIKE','%'.$r->search.'%');
        })->with('lastCounts','groupMaster')->skip($r->offset)->limit($r->limit)->orderByRaw('FIELD(id,'.$order_array.')')->get();
              
    }else{
        $sosmed = AlexaMaster::where('group_id',$operat2,$r->group)->where(function($query) use ($r){
            $query->where('alexa_name','LIKE','%'.$r->search.'%')
            ->orwhere('alexa_group','LIKE','%'.$r->search.'%');
        })->with('lastCounts','groupMaster')->skip($r->offset)->limit($r->limit)->orderBy($r->orderByCol, $r->orderByAct)->get();
    
    }
    json_true($sosmed);
}

}
