<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \GuzzleHttp\Client;
use App\Models\AlexaMaster;
use App\Models\AlexaLastRetrieved;
use URL;

class RetrieveAlexa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retrieve:alexa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Alexa Retriever';

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
        $alexalist = AlexaMaster::all();
        // dd($alexalist);
        if(count($alexalist)>0){
            // dump($alexalist);
            $log_data=[];
            $today = date('Y-m-d');
            foreach($alexalist as $key){
                $request = new Client();
                $username = preg_replace('#(^https?:\/\/(w{3}\.)?)|(\/$)#', '', $key->alexa_url);
                dump($username);
                $url = url("/api/alexa/".$username);
                $response = json_decode($request->get($url)->getBody()->getContents());
                dump($response->data);
                if($response->result==true){
                    if($response->data->alexa_rank==0 || $response->data->alexa_local_rank==0 ){
                        
                        $log["id"] = $key->id;
                        $log["alexa_url"] = $key->alexa_url;
                        $log["error_date"] = $today; 
                        
                        array_push($log_data,$log);
                    }else{
                    $cek = AlexaLastRetrieved::where('alexa_id',$key->id)->whereraw("cast(created_at as date ) = '$today' ")->count();
                    // dump($cek);
                    if($cek > 0){
                        AlexaLastRetrieved::where("alexa_id",$key->id)
                        ->whereraw("cast(created_at as date ) = '$today' ")
                        ->update([
                            "alexa_rank" => $response->data->alexa_rank,
                            "alexa_local_rank" => $response->data->alexa_local_rank,
                            "alexa_locale_code" => $response->data->locale_code
                        ]);
                    }else{
                        AlexaLastRetrieved::create([
                            "alexa_id" => $key->id,
                            "alexa_rank" => $response->data->alexa_rank,
                            "alexa_local_rank" => $response->data->alexa_local_rank,
                            "alexa_locale_code" => $response->data->locale_code
                        ]);
                    }
                  }
                   
                }
                // break;
            }
            // dump(json_encode($log_data));
            if(count($log_data)>0){
            file_put_contents('public/logs/alexa-'.$today.'.json',json_encode($log_data));
            }
        }
    }
}
