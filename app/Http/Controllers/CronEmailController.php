<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CronEmail;
use App\Models\GroupMaster;
use App\Models\SocmedAllowedUsers;


class CronEmailController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('IsAdmin');
    }

    function index()
    {
        $data["group"] = GroupMaster::where('group_status','1')->orderBy('id','asc')->get();
        
       return View("configurations.cron_email",$data);
    }

    public function action(Request $r)
    {
        $validate = $r->validate([
            'email_subject' => 'required',
            'email_recipient' => 'required',
            'email_cron1' => 'required',
            'email_cron2' => 'required',
        ]);
        
        $email_recipient = preg_replace('#\s+#',',',trim($r->email_recipient));
        $email_grouplist = implode(',',$r->email_grouplist1);
        $email_cron = $r->email_cron1.",".$r->email_cron2;

        $data = [
            'email_status' => $r->email_status,
            'email_subject' => $r->email_subject,
            'email_recipient' =>  $email_recipient,
            'email_cron' => $email_cron,
            'email_grouplist' => $email_grouplist
        ];
        
        
        if($r->action=="add")
        {
            CronEmail::create($data);
        }
        else if($r->action=="update")
        {
            CronEmail::where('id',$r->id)->update($data);
        }
        else
        {
            print json_encode("error");
        }
        
       print json_encode("success");
    }

    public function detail_cron(Request $r)
    {
        $validate = $r->validate([
            'id' => 'required',
        ]);
        
        $response = CronEmail::where('id',$r->id)->get();
        json_true($response);
    }

    public function delete($id)
    {
        CronEmail::find($id)->delete();
        print json_encode("success");
    }

    public function count_cron(Request $r)
    {
        $count = CronEmail::where('email_subject','LIKE','%'.$r->search.'%')->count();
        json_true($count);
    }
    public function get_cron(Request $r)
    {
    
        $sosmed = CronEmail::where('email_subject','LIKE','%'.$r->search.'%')->skip($r->offset)->limit($r->limit)->get();
        json_true($sosmed);
    }

   

}
