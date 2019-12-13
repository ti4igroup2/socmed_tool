<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupMaster;
use App\Models\SocmedAllowedUsers;


class GroupMasterController extends Controller
{


    function index()
    {
       return View("configurations.group");
    }

 
    public function action(Request $r)
    {
        $validate = $r->validate([
            'group_name' => 'required',
            'group_type' => 'required',
            'group_status' => 'required',
        ]);
        
        
        if(isset($r->group_order))
        {
            $group_order = $r->group_order;
        }
        else
        {
            $max = GroupMaster::selectRaw('max(group_order) as max')
            ->where('group_type',$r->group_type)
            ->first();
            $group_order = $max->max + 1;
        }
        
        
        $data = [
            'group_name' => $r->group_name,
            'group_type' =>  $r->group_type,
            'group_status' => $r->group_status,
            'group_order' => $group_order
        ];
        
        
        if($r->action=="add")
        {
            GroupMaster::create($data);
        }
        else if($r->action=="update")
        {
            GroupMaster::where('id',$r->id)->update($data);
        }
        else
        {
            print json_encode("error");
        }
        
        
       print json_encode("success");
    }

    public function updateOrder(Request $r)
    {
        $validate = $r->validate([
            'id' => 'required',
        ]);
        
        
        GroupMaster::where('id',$r->id)
        ->update(["group_order"=>$r->order]);
        
        print json_encode("success");
    }

    public function detail_group(Request $r)
    {
    
        $validate = $r->validate([
            'id' => 'required',
        ]);
        
        $response = GroupMaster::where('id',$r->id) ->get();
        
        json_true($response);
    }

    public function delete($id)
    {
    
        GroupMaster::find($id)->delete();
        print json_encode("success");
    }


    public function count_group(Request $r)
    {
        if($r->status == "")
        {
            $operat = "!=";
        }else{
            $operat = "=";
        }
        
        
        if($r->group==""){$operat2 = "!="; }else{$operat2 = "=";}
        
        $count = GroupMaster::where('group_status',$operat,$r->status)
        ->where('group_name','LIKE','%'.$r->search.'%')
        ->count();
        
        json_true($count);
    }
    
    
    public function get_group(Request $r)
    {
        if($r->status == ""){
            $operat = "!=";
        }else{
            $operat = "=";
        }
        
        if($r->group==""){$operat2 = "!="; }else{$operat2 = "=";}
        
        $sosmed = GroupMaster::where('group_status',$operat,$r->status)
        ->where('group_name','LIKE','%'.$r->search.'%')
        ->orderBy($r->orderByCol,$r->orderByAct)
        ->skip($r->offset)->limit($r->limit)->get();
        
        json_true($sosmed);
    }


    public function get_groupBySocmed()
    {
        $group = GroupMaster::where('group_type','socmed')->get();
        json_true($group);
    }

    public function get_groupByAlexa()
    {
        $group = GroupMaster::where('group_type','alexa')->get();
        json_true($group);
    }

}
