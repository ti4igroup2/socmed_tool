<?php

namespace App\Http\Controllers;

use Goutte;

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

	private function json_true($data){
		$data = array(
				"result" => true,
				"data" => $data
		);
		return json_encode($data);
	}
}
