<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \GuzzleHttp\Client;
use App\Models\SocmedMaster;
use App\Models\SocmedLastRetrieved;
use App\Models\AlexaMaster;
use App\Models\AlexaLastRetrieved;
use Config,Goutte,Artisan;

class RetrieveController extends Controller
{
    protected $fb_token,$yt_token;
    public function __construct()
    {
        // $this->fb_token = "EAAhoKcd8WTEBAO2OyjpozHlPHoNlKxiFCZBOvT2P94uxIH8SqR2c7V9oMkbX6PNkagrolsnIRFLBv0uYw3vgra8Ar7ZBxGtzX82RVeUHY1rDZAbfuXlS7R9hqVo1QP0qs3y3ZBrycY6Ml5bw2GdfyoTcwRZAFcViqmKKvbinfewZDZD";
        $this->yt_token = Config::get('socmed_key.google_key.key');
    }


    //twitter
    public function twitter($username)
    {
        $request = new Client();
        $response = json_decode($request->get("https://cdn.syndication.twimg.com/widgets/followbutton/info.json?screen_names=".$username)
                    ->getBody()->getContents());
        $data = array(
            "nama" => $response[0]->name,
            "total" => $response[0]->followers_count,
        );
        return $this->json_true($data);
    }

    //facebook
    public function facebook($page_name){
        // https://api-green.likealyzer.com/api/search/?q=liputan6online/&queryFacebook=true /alternatif bisa semua akun tanpa token
        // $request = new Client();
        // $response = json_decode($request->get("https://api-green.likealyzer.com/api/report/".$page_name)
        //             ->getBody()->getContents());
        // if($response->data){
        // $data = array(
        //     "nama" => $response->data->name,
        //     "total" => $response->data->results->engagement->metrics->total_page_likes->value,
        // );
        // return $this->json_true($data);
        // }
        $crawler = Goutte::request('GET', 'https://facebook.com/'.$page_name);
        if($crawler->filter('._2pi9._2pi2 div.clearfix._ikh > div._4bl9')->count() > 0 ){
        $likes = $crawler->filter('._2pi9._2pi2 div.clearfix._ikh > div._4bl9')->text();
         $output = preg_replace('/[^0-9]/', '', $likes);
        }else{
            $output = "0";
        }
        
        $data = array(
            "nama" => $page_name,
            "total" => $output,
        );
        return $this->json_true($data);

    }


    // youtube
    public function youtube($channel_name){
        $request = new Client();
        $response = json_decode($request->get("https://www.googleapis.com/youtube/v3/search?part=snippet&q=".$channel_name."&type=channel&fields=items/snippet&key=".$this->yt_token)
                    ->getBody()->getContents());
        if(count($response->items)>0){
            $channel_id = $response->items[0]->snippet->channelId;
            $nama = $response->items[0]->snippet->title;
            $response = json_decode($request->get('https://www.googleapis.com/youtube/v3/channels?part=statistics&id='.$channel_id.'&fields=items/statistics/subscriberCount&key='.$this->yt_token)
                    ->getBody()->getContents());
            $data = array(
                "nama" => $nama,
                "total" => $response->items[0]->statistics->subscriberCount
            );
            return $this->json_true($data);
        }else{
            // cath error
        }
    }

    // instagram
    public function instagram($username){
        $request = new Client();
        $response = json_decode($request->get("https://www.instagram.com/".$username."/?__a=1")
                    ->getBody()->getContents());
        // dd($response);
        $data = array(
            "nama" => $response->graphql->user->full_name,
            "total" => $response->graphql->user->edge_followed_by->count
        );
       return $this->json_true($data);
    }

    //Alexa
    public function alexa($domain){
        $request = new Client();
        $response = $request->get("http://data.alexa.com/data?cli=10&dat=snbamz&url=".$domain)
                    ->getBody()->getContents();
        $xml = new \SimpleXmlElement($response);
        if (isset($xml->SD[1]->POPULARITY))
        {
            $data['alexa_rank'] = (int) $xml->SD[1]->POPULARITY->attributes()->TEXT;
            // $data['alexa_rank'] = 0;
            $data['alexa_local_rank'] = (int) $xml->SD[1]->COUNTRY->attributes()->RANK;
            $data['locale_code'] = (String)$xml->SD[1]->COUNTRY->attributes()->CODE;
        }
        else
        {
            $data['alexa_rank'] = '0';
            $data['alexa_local_rank'] = '0';
            $data['locale_code'] = '-';
            
        }
        return $this->json_true($data);
    }


    //By ID
    public function retrieveById(Request $r){
        $alexalist = SocmedMaster::where('id',$r->id)->get();
        if(count($alexalist)>0){
            foreach($alexalist as $key){
                $request = new Client();
                $username = UrlToUsername($key->socmed_url,$key->socmed_type);
                $url = url("/api/".$key->socmed_type."/".$username);
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

            print json_true("success");
        }

    }

    public function retrieveAlexaById(Request $r){
        $alexalist = AlexaMaster::where('id',$r->id)->get();
        if(count($alexalist)>0){
            foreach($alexalist as $key){
                $request = new Client();
                $username = preg_replace('#(^https?:\/\/(w{3}\.)?)|(\/$)#', '', $key->alexa_url);
                $url = url("/api/alexa/".$username);
                $response = json_decode($request->get($url)->getBody()->getContents());
                // dd($response);
                if($response->result==true){
                    $today = date('Y-m-d');
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
            }
            print json_true("success");
        }
    }

    public function mailreport(Request $r){
        Artisan::call("mail:report --user=".$r->user);
        print json_true("success");
    }



//class function
    private function json_true($data){
        $data = array(
            "result" => true,
            "data" => $data
        );
        return json_encode($data);
    }
}
