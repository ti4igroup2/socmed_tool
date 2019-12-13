<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Auth;
use Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }




    public function redirectToProvider()
    {
        return Socialite::driver('google')
        // ->with(['hd' => 'kly.id'])
        ->redirect();
    }

    /**
     * Obtain the user information from google.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('google')->stateless()->user();
        // if(isset($user->user['hd'])){
           $check = DB::table('allowed_users')->select('role')->where('user_email',$user->email)->get();
           if(count($check)>0){
        // dd($user);
        $add = $this->updateOrInsert($user,"1",$check[0]->role);
           Auth::login($add);
            return redirect('/dashboard');
           }else{
            $this->updateOrInsert($user,"0","user");
               dd("Users Not Allowed");
           }
        // }else{
        //     dd("Email Invalid");
        // }
    }

    private function updateOrInsert($user,$valid,$role){
        $data = [
            "user_email"=>$user->email,
            "user_realname" => $user->name,
            "user_photo" => $user->avatar,
            "user_role" => $role,
            "user_isValid" => $valid,
            "remember_token" => $user->token];
         User::updateOrCreate(["user_email"=>$user->email],$data);
         $result = User::where([["user_email",$user->email],["user_role",$role]])->first();
        return $result;
    }

}
