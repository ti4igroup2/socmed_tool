<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SocmedMaster;
use App\Models\SocmedAllowedUsers;


class AllowedUserController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('IsAdmin');
    }

    function index(){
       return View("configurations.user_allow");
    }

    // AJAX SERVICE
    public function action(Request $r){
        $validate = $r->validate([
            'user_email' => 'required',
            'user_role' => 'required'
        ]);
        $data = [
            'role' => $r->user_role,
            'user_email' =>  $r->user_email,
        ];
        if($r->action=="add"){
            SocmedAllowedUsers::create($data);
        }else if($r->action=="update"){
            SocmedAllowedUsers::where('id',$r->id)->update($data);
        }else{
            print json_encode("error");
        }
       print json_encode("success");
    }

    public function detail_users(Request $r){
        $validate = $r->validate([
            'id' => 'required',
        ]);
        $response = SocmedAllowedUsers::where('id',$r->id)->get();
        json_true($response);
    }

    public function delete($id){
        SocmedAllowedUsers::find($id)->delete();
        print json_encode("success");
    }


    public function count_users(Request $r){
        $count = SocmedAllowedUsers::where('user_email','LIKE','%'.$r->search.'%')->count();
        json_true($count);
    }
    public function get_users(Request $r){
  
        $sosmed = SocmedAllowedUsers::where('user_email','LIKE','%'.$r->search.'%')->skip($r->offset)->limit($r->limit)->get();
        json_true($sosmed);
    }

}
