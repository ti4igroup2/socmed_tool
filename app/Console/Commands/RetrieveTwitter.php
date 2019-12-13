<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \GuzzleHttp\Client;
use App\Models\SocmedMaster;
use App\Models\SocmedLastRetrieved;
use URL;

class RetrieveTwitter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retrieve:twitter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Twitter Retriever';

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
        $twlist = SocmedMaster::where('socmed_type','twitter')->where('socmed_status',"1")->get();
        if(count($twlist)>0){
            foreach($twlist as $key){
                $request = new Client();
                $username = UrlToUsername($key->socmed_url,"twitter");
                $url = url("/api/twitter/".$username);
                $response = json_decode($request->get($url)->getBody()->getContents());
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
