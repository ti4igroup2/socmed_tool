<?php

namespace App\Http\Controllers;

use Goutte;
use \GuzzleHttp\Client;

use Illuminate\Http\Request;

class RetrieveCtrl extends Controller
{

	protected $fb_token,$yt_token;
    public function __construct()
    {
        $this->yt_token = "AIzaSyDxqY1SiTnm3kL_XvJ91owIJmnTox74BfA";
    }


    public function facebook($page_name)
	{
		$crawler = Goutte::request('GET','https://facebook.com/'.$page_name);
		if ($crawler->filter('._2pi9._2pi2 div.clearfix._ikh > div._4bl9')->count() > 0) {
			$likes = $crawler->filter('._2pi9._2pi2 div.clearfix._ikh > div._4bl9')->text();
			$output = preg_replace('/[^0-9]/','',$likes);
		}else{
			$output = "0";
		}
		$data = array(
				"nama" => $page_name,
				"total" => $output,
		);
		return $this->json_true($data);
	}

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





	private function json_true($data){
		$data = array(
				"result" => true,
				"data" => $data
		);
		return json_encode($data);
	}
}
