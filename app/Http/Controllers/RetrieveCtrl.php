<?php

namespace App\Http\Controllers;

use Goutte;
use \GuzzleHttp\Client;

use Illuminate\Http\Request;

class RetrieveCtrl extends Controller
{
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




	private function json_true($data){
		$data = array(
				"result" => true,
				"data" => $data
		);
		return json_encode($data);
	}
}
