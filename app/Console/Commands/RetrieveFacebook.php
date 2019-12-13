<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \GuzzleHttp\Client;
use App\Models\SocmedMaster;
use App\Models\SocmedLastRetrieved;
use URL;

class RetrieveFacebook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retrieve:facebook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Facebook Retriever';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $fblist = SocmedMaster::where('socmed_type','facebook')->where('socmed_status',"1")->get();
        if(count($fblist)>0){
            foreach($fblist as $key){
                $request = new Client();
                $username = UrlToUsername($key->socmed_url,"facebook");
                $url = url("/api/facebook/".$username);
                $response = json_decode($request->get($url)->getBody()->getContents());
                if($response->result==true){
                    $today = date('Y-m-d');
                    $cek = SocmedLastRetrieved::where('socmed_id',$key->id)->whereraw("cast(created_at as date ) = '$today' ")->count();
                    if($response->data->total==0){
                        SocmedMaster::where('socmed_id',$key->id)->update([
                            "socmed_status" => "0"
                        ]);
                    }else{
                    if($cek > 0){
                        SocmedLastRetrieved::where("socmed_id",$key->id)
                        ->whereraw("cast(created_at as date ) = '$today' ")
                        ->update([
                            "socmed_total" => $response->data->total
                        ]);
                    }else{
                        SocmedLastRetrieved::create([
                            "socmed_id" => $key->id,
                            "socmed_total" => $response->data->total
                        ]);
                    }
                  }
                   
                }
            }
        }
    }
}
