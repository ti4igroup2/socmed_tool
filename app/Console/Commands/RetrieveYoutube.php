<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \GuzzleHttp\Client;
use App\Models\SocmedMaster;
use App\Models\SocmedLastRetrieved;
use URL,Config;

class RetrieveYoutube extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retrieve:youtube';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Youtube Retriever';

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
        $twlist = SocmedMaster::where('socmed_type','youtube')->where('socmed_status',"1")->get();
        if(count($twlist)>0){
            foreach($twlist as $key){
                $request = new Client();
                $username = UrlToUsername($key->socmed_url,"youtube");
                $url = url("/api/youtube/".$username);
                $response = json_decode($request->get($url)->getBody()->getContents());
                // dd($username);
                if($response->result==true){
                    $today = date('Y-m-d');
                    $cek = SocmedLastRetrieved::where('socmed_id',$key->id)->whereraw("cast(created_at as date ) = '$today' ")->count();
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
