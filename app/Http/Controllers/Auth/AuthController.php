<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use Socialite;
use \GuzzleHttp\Client;
use App\Models\SocmedAllowedUsers;
use App\Models\Fanspage;
use Auth,Artisan;

class AuthController extends Controller
{
    protected $graphUrl = 'https://graph.facebook.com/v4.0/';
    /**
     * Redirect the user to the google authentication page.
     *
     * @return Response
     */
    public function redirectFacebook(){
        
        return  Socialite::driver("facebook")->scopes([
            'email','manage_pages','pages_messaging_phone_number','pages_show_list','pages_messaging','pages_messaging_subscriptions'
            ])->redirect()->header('Host','tabanan.merdeka.com');
    }

    /**
     * Obtain the user information from google.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('facebook')->stateless()->user();
        $old_fbid = SocmedAllowedUsers::select('fb_id')->where('user_email',auth()->user()["user_email"])->get();
        // dd($old_fbid);
        $user_id = SocmedAllowedUsers::select('id')->where('user_email',auth()->user()["user_email"])->get();
        if($user->id==$old_fbid[0]->fb_id){
        SocmedAllowedUsers::where('user_email',auth()->user()["user_email"])->update([
            "access_token" => $user->token
            ]);
        $this->FanspageAction($user_id[0]->id,$user->token);
        return redirect('/fbpage');
        }else{
          SocmedAllowedUsers::where('user_email',auth()->user()["user_email"])->update([
            "fb_id" => $user->id,
            "access_token" => $user->token
          ]);
          $this->FanspageAction($user_id[0]->id,$user->token);
          return redirect('/fbpage');
        }
        // dd($user);
    }

    public function FanspageAction($id,$token){
        $request = new Client();
        $response = json_decode($request->get($this->graphUrl."me/accounts?&access_token=".$token)
                    ->getBody()->getContents());

        if(count($response->data)>0){
            foreach($response->data as $fp){
                $cek = Fanspage::where('fanpage_id',$fp->id)->get();
                if(count($cek)>0){
                    Fanspage::where('fanpage_id',$fp->id)->update([
                        "access_token" => $fp->access_token,
                        "fanpage_name" => $fp->name,
                        "user_id" => $id,
                        "fanpage_status" => "1"
                    ]);
                }else{
                    Fanspage::create([
                        "access_token" => $fp->access_token,
                        "fanpage_name" => $fp->name,
                        "fanpage_id" => $fp->id,
                        "user_id" => $id,
                        "fanpage_status" => "1"
                    ]);
                }
            }
        }
        
    }
}