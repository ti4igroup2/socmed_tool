<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Command;
use \GuzzleHttp\Client;
use App\Models\Fanspage;
use App\Models\Post;
use URL,Exception;

class RetrievePost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retrieve:post {--fpid=} {--token=} {--time=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Facebook Fanspage Post Retriever';
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
        $fpid = $this->option('fpid');
        $token = $this->option('token');
        if($this->option('time')=="today"){
            $fplist = Fanspage::where('fanpage_status',"1")->get();
            foreach($fplist as $r){
                $this->getPost($r->fanpage_id,$r->access_token,'today','');
            }

            // per fanpage bergiliran
            // $file = Storage::disk('local')->exists('last_retrieved.txt');
            // if($file)
            // {
            //     $last_retrieved = json_decode(Storage::disk('local')->get('last_retrieved.txt'), true);
            //     if($last_retrieved['next'] == '')
            //     {
            //         $fp = Fanspage::where('id', '>', $last_retrieved['id'])->where('fanpage_status', '1')->orderBy('id', 'ASC')->first();
            //         if(!$fp) $fp = Fanspage::where('fanpage_status', '1')->orderBy('id', 'ASC')->first();
                
            //         $this->getPost($fp->fanpage_id,$fp->access_token,'today','');
            //     }
            //     else
            //     {
            //         $this->getPost($last_retrieved['fanpage_id'],'','',$last_retrieved['next']);
            //     }
            // }
            // else
            // {
            //      $fp = Fanspage::where('fanpage_status', '1')->orderBy('id', 'ASC')->first(); 
            //      $this->getPost($fp->fanpage_id,$r->access_token,'today','');
            // }

        }else{
        $this->getPost($fpid,$token,'','');
        }
    }

    // public function logged_in($token)
    // {
    //     $request = new Client();
    //     $response = json_decode($request->get($this->graphUrl."me?fields=id,name,first_name,email,picture.type(normal)&access_token=".$token)
    //                 ->getBody()->getContents());

    //     return $response;
    // }

    // public function page_list($token)
    // {
    //     $request = new Client();
    //     $response = json_decode($request->get($this->graphUrl."me/accounts?&access_token=".$token)
    //                 ->getBody()->getContents());

    //     return $response->data;
    // }

    public function getPost($fpid,$token,$limit,$next)
    {
        $today = date('Y-m-d',strtotime("+1 days"));
        $threedays = date('Y-m-d',strtotime("-3 days"));

        if($next!="")
        {
            $url = $next;
        }else
        {
            if($limit=="today")
            {
                $url = $this->graphUrl.$fpid."/feed?limit=50&fields=message,full_picture,created_time,admin_creator,reactions.type(WOW).summary(true).as(wow),reactions.type(LOVE).summary(true).as(love),reactions.type(HAHA).summary(true).as(haha),reactions.type(SAD).summary(true).as(sad),reactions.type(ANGRY).summary(true).as(angry),comments.limit(1).summary(true),likes.limit(1).summary(true),shares&since=".$threedays."&until=".$today."&access_token=".$token;
            }else
            {
                $url = $this->graphUrl.$fpid."/feed?limit=100&fields=message,admin_creator,full_picture,created_time,reactions.type(WOW).summary(true).as(wow),reactions.type(LOVE).summary(true).as(love),reactions.type(HAHA).summary(true).as(haha),reactions.type(SAD).summary(true).as(sad),reactions.type(ANGRY).summary(true).as(angry),comments.limit(1).summary(true),likes.limit(1).summary(true),shares&access_token=".$token;
            }
        }
     
        try
        {
            $request = new Client();
            $response = json_decode($request->get($url)
                    ->getBody()->getContents());
        
        if(count($response->data)>0)
        {
            // dump($response->data[0]->id);
            for($i=0;$i<count($response->data);$i++)
            {
             $top_post = $i;
             $strid = explode('_',$response->data[$top_post]->id);
            $cek = Post::select('id')->where('id',$strid[1])->get();
            $data = array(
                "fanpage_id" => $fpid,
                "admin_creator" => (isset($response->data[$top_post]->admin_creator->name))?$response->data[$top_post]->admin_creator->name:"Admin",
                "shares" => (isset($response->data[$top_post]->shares->count))?$response->data[$top_post]->shares->count:"0",
                "created_time" => date('Y-m-d H:i:s',strtotime($response->data[$top_post]->created_time)),
                "full_picture" => (isset($response->data[$top_post]->full_picture))?$response->data[$top_post]->full_picture:"http://placehold.it/800x400",
                "message" => (isset($response->data[$top_post]->message))?substr($response->data[$top_post]->message,0,50):"",
                "wow" => $response->data[$top_post]->wow->summary->total_count,
                "love" => $response->data[$top_post]->love->summary->total_count,
                "haha" => $response->data[$top_post]->haha->summary->total_count,
                "sad" => $response->data[$top_post]->sad->summary->total_count,
                "angry" => $response->data[$top_post]->angry->summary->total_count,
                "id" => $strid[1],
                "comments" => $response->data[$top_post]->comments->summary->total_count,
                "likes" => $response->data[$top_post]->likes->summary->total_count
            );
            if(count($cek)>0)
            {
                unset($data["id"]);
                Post::where('id',$strid[1])->update($data);
                // dump('sukses update');
            }else{
                Post::create($data);
                // dump('sukses simpan');
            }
            }
            if($limit=='today')
            {
                if(isset($response->paging->next))
                {
                    $this->getPost($fpid,'','',$response->paging->next);
                }
            }
        }else{
            // dump('kosong');
        }
      }catch(Exception $e){
          Fanspage::where('fanpage_id',$fpid)->update([
            "fanpage_status" => "0"
          ]);
      }
    }
}
