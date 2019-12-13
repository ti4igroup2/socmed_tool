<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \GuzzleHttp\Client;
use App\Models\AlexaMaster;
use App\Models\AlexaLastRetrieved;
use URL,Exception;

class RetrieveAlexaError extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retrieve:alexa_error';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Alexa Retriever Error';

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
        $listdir = glob("public/logs/*.json");
        // dump($listdir[0]);
        if(count($listdir)>7){
            unlink($listdir[0]);
        }

        
        $today = date('Y-m-d');

        try{
           
            $alexalist = json_decode(file_get_contents('public/logs/alexa-'.$today.'.json'));
            if(count($alexalist)>0){
                foreach($alexalist as $key){
                    $request = new Client();
                    $username = preg_replace('#(^https?:\/\/(w{3}\.)?)|(\/$)#', '', $key->alexa_url);
                    dump($username);
                    $url = url("/api/alexa/".$username);
                    $response = json_decode($request->get($url)->getBody()->getContents());
                    dump($response->data);
                    
                        $cek = AlexaLastRetrieved::where('alexa_id',$key->id)->whereraw("cast(created_at as date ) = '$today' ")->count();
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
                // dump(json_encode($log_data)
                }

        }catch(Exception $e){
            dump("kosong");
        }        
    }
}
