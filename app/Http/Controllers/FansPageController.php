<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \GuzzleHttp\Client;
use App\Models\Fanspage;
use App\Models\Post;
use App\Models\SocmedAllowedUsers;
use Config,Auth,Artisan,Exception;


class FansPageController extends Controller
{
    protected $u_id;

    function index()
    {
        return View("fanspage.index");
    }

    function detail($id)
    {
        return View("fanspage.detail");
    }

    public function count_fanspage(Request $r)
    {
        $cek = SocmedAllowedUsers::select('id')
                    ->where('user_email',auth()->user()["user_email"])
                    ->get();
        
        $count = Fanspage::where('fanpage_name','LIKE','%'.$r->search.'%')->count();
        
        json_true($count);
    }

    public function get_fanspage(Request $r)
    {
        $cek = SocmedAllowedUsers::select('id')
        ->where('user_email',auth()->user()["user_email"])->get();
        
        $sosmed = Fanspage::where('fanpage_name','LIKE','%'.$r->search.'%')
                    ->skip($r->offset)
                    ->limit($r->limit)
                    ->get();
        
        for($i=0;$i<count($sosmed);$i++)
        {
            $sosmed[$i]["owned"] = (($sosmed[$i]["user_id"]==$cek[0]->id)?"owned":"no");
        }
            
        json_true($sosmed);
    }

    public function count_post(Request $r)
    {
        $cek = Fanspage::select('fanpage_id')->where('id',$r->fpid)->get();
        
        $count = Post::where(function($query) use ($r){
                $query->where('message','LIKE','%'.$r->search.'%')
                ->orwhere('admin_creator','LIKE','%'.$r->search.'%');
            })
            ->where('created_time','LIKE','%'.$r->dateSearch.'%')
            ->where('fanpage_id',$cek[0]->fanpage_id)
            ->count();
            
        json_true($count);
    }

    public function get_post(Request $r)
    {
        $cek = Fanspage::select('fanpage_id')->where('id',$r->fpid)->get();
        $sosmed = Post::where(function($query) use ($r){
                    $query->where('message','LIKE','%'.$r->search.'%')
                    ->orwhere('admin_creator','LIKE','%'.$r->search.'%');
                })
                ->where('created_time','LIKE','%'.$r->dateSearch.'%')
                ->where('fanpage_id',$cek[0]->fanpage_id)
                ->skip($r->offset)->limit($r->limit)
                ->orderByRaw($r->orderByCol." ".$r->orderByAct)
                ->get();
        
        for($i=0;$i<count($sosmed);$i++)
        {
            $sosmed[$i]["datepost"] = date('d M Y H:i',strtotime($sosmed[$i]->created_time));
        }
    
        json_true($sosmed);
    }

    public function reloadallpost(Request $r)
    {
        $cek = Fanspage::select(['fanpage_id','access_token'])->where('id',$r->fpid)->get();
        Artisan::call('retrieve:post --fpid="'.$cek[0]->fanpage_id.'" --token="'.$cek[0]->access_token.'"');
        
        json_true($cek);
    }

    public function updateStatus(Request $r)
    {
        $response = "";
        $cek = Fanspage::select('fanpage_status','access_token')->where('id',$r->id)->get();
        
        if($cek[0]->fanpage_status=="1")
        {
            $update = Fanspage::where('id',$r->id)->update([
                "fanpage_status" => "0"
            ]);
            
            if($update)
            {
                $response = "off";
            }
        }
        else if($cek[0]->fanpage_status=="0")
        {
            $token = $cek[0]->access_token;
            $request = new Client();
            try{
                $response = json_decode($request->get("https://graph.facebook.com/me?access_token=".$token)
                        ->getBody()->getContents());
                if(isset($response->name)){
                    $update = Fanspage::where('id',$r->id)->update([
                        "fanpage_status" => "1"
                    ]);
                    if($update){
                        $response = "on";
                    }
                }
            }catch(Exception $e){
                $response = "error_token";
            }
        }else{
                $response = "error";
        }

        json_true($response);
    }
    
}