<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \GuzzleHttp\Client;
use App\Models\SocmedAllowedUsers;
use URL,Exception;

class RetrieveFanspage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retrieve:fanspage {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Facebook Fanspage Retriever';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $graphUrl = 'https://graph.facebook.com/v4.0/';
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
        if($this->option('id')!=""){
            $users = SocmedAllowedUsers::select('fb_id','access_token')->where('fb_id',$this->option('id'))->get();
        }else{
            //
            $users = SocmedAllowedUsers::select('fb_id','access_token')->get();
        }
        // dd($users);
        foreach($users as $user){
            if($user->fb_id!=null){
                try{
                    $data["logged_in"] = $this->logged_in($user->access_token);
                    $page_list = $this->page_list($user->access_token);
                    $data["top_post"] = $this->last_post($page_list,$user->access_token);
                    $data["page_list"] = $page_list;
                    // dump($data);
                        file_put_contents(public_path('fp/'.$user->fb_id.'.txt'),json_encode($data));
                        // dump("saved");
                }catch(Exception $e){
                    dump('error');
                }      
                
                // dump("saved");
            }else{
                dump("kosong");
            }
        }
    }

    public function logged_in($token)
    {
        $request = new Client();
        $response = json_decode($request->get($this->graphUrl."me?fields=id,name,first_name,email,picture.type(normal)&access_token=".$token)
                    ->getBody()->getContents());

        return $response;
    }

    public function page_list($token)
    {
        $request = new Client();
        $response = json_decode($request->get($this->graphUrl."me/accounts?&access_token=".$token)
                    ->getBody()->getContents());

        return $response->data;
    }

    public function last_post($page_list,$token){
        $result = [];
        foreach($page_list as $list){
            $request = new Client();
            $response = json_decode($request->get($this->graphUrl.$list->id."/feed?limit=10&fields=message,full_picture,created_time,reactions.type(WOW).summary(true).as(wow),reactions.type(LOVE).summary(true).as(love),reactions.type(HAHA).summary(true).as(haha),reactions.type(THANKFUL).summary(true).as(thankful),reactions.type(ANGRY).summary(true).as(angry),reactions.type(PRIDE).summary(true).as(pride),comments.limit(1).summary(true),likes.limit(1).summary(true),shares&access_token=".$token)
                    ->getBody()->getContents());
           if(count($response->data)>0){
                $likes = [];
                $comment= [];
                $share= [];
                $total_contribute = [];
                $wow=$love=$haha=$thankful=$angry=$pride = [];
            // dd($response->data[0]->message);
                for($i=0;$i<count($response->data);$i++){
                    if(isset($response->data[$i]->shares->count)){
                        $share[$i] = $response->data[$i]->shares->count;
                    }else{
                        $share[$i] = 0;
                    }
                    $wow[$i] = $response->data[$i]->wow->summary->total_count;
                    $love[$i] = $response->data[$i]->love->summary->total_count;
                    $haha[$i] = $response->data[$i]->haha->summary->total_count;
                    $thankful[$i] = $response->data[$i]->thankful->summary->total_count;
                    $angry[$i] = $response->data[$i]->angry->summary->total_count;
                    $pride[$i] = $response->data[$i]->pride->summary->total_count;
                    $likes[$i] = $response->data[$i]->likes->summary->total_count;
                    $comment[$i] = $response->data[$i]->comments->summary->total_count;
                    $total_contribute[$i] = $wow[$i]+$love[$i]+$haha[$i]+$thankful[$i]+$angry[$i]+$pride[$i]+$share[$i]+$likes[$i]+$comment[$i];
                }
    
                arsort($total_contribute);
                $top_post = key($total_contribute);

                if(isset($response->data[$top_post]->shares->count)){
                    $shares = $response->data[$top_post]->shares->count;
                }else{
                    $shares = 0;
                }
                
                $data = array(
                    "shares" => $shares,
                    "created_time" => $response->data[$top_post]->created_time,
                    "full_picture" => (isset($response->data[$top_post]->full_picture))?$response->data[$top_post]->full_picture:"http://placehold.it/800x400",
                    "message" => (isset($response->data[$top_post]->message))?$response->data[$top_post]->message:"",
                    "wow" => $response->data[$top_post]->wow->summary->total_count,
                    "love" => $response->data[$top_post]->love->summary->total_count,
                    "haha" => $response->data[$top_post]->haha->summary->total_count,
                    "thankful" => $response->data[$top_post]->thankful->summary->total_count,
                    "angry" => $response->data[$top_post]->angry->summary->total_count,
                    "pride" => $response->data[$top_post]->pride->summary->total_count,
                    "likes" => $response->data[$top_post]->likes->summary->total_count,
                    "id" => $response->data[$top_post]->id,
                    "comments" => $response->data[$top_post]->comments->summary->total_count,
                    "likes" => $response->data[$top_post]->likes->summary->total_count
                );
            }else{
                $data = array(
                   "id" => 0
                );
            }  

            $result[] = $data;
        }

    return $result;
}
}
