<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \GuzzleHttp\Client;
use App\Models\SocmedMaster;
use App\Models\CronEmail;
use App\Models\SocmedLastRetrieved;
use App\Models\AlexaMaster;
use App\Models\AlexaLastRetrieved;
use App\Models\GroupMaster;
use URL,Mail;
Use Exception;

class MailReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:report {--user=} {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Growth Mail Reporter';

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
        if($this->option('id')!=""){
            $id = $this->option('id');
        }else{
            //
            $id = "1";
        }
        // dd($id);
        $cronsetting = CronEmail::where('id',$id)->get();
        
        // dd($cronsetting);
        // "
        if($this->option('user')!=""){
            $user = explode(',', $this->option('user'));
        }else{
            //
            $user = explode(',',$cronsetting[0]->email_recipient);
        }
        $group_list = explode(',',$cronsetting[0]->email_grouplist);

        $data["to"] = $user;
        $data["email"] = "noreply@kapanlagi.com"; //"noreply@kly.id";
        $subject_date = str_replace('%DAY'," ".date('d'),strtoupper($cronsetting[0]->email_subject));
        $subject_month = str_replace('%MONTH'," ".date('F'),strtoupper($subject_date));
        $subject_year = str_replace('%YEAR'," ".date('y'),strtoupper($subject_month));
        $subject = str_replace('%DATE'," ".date('d F y'),strtoupper($subject_year));
        $data["subject"] = strtoupper($subject);
    
        try{
            $list = GroupMaster::whereIn('id',$group_list)->where('group_type','socmed')->where('group_status','1')->orderBy('group_order','asc')->get()->pluck('id')->toArray();
            $order_array = implode(",",$list);
            $myData = SocmedMaster::whereIn('group_id',$list)->with(['groupMaster'])->orderByRaw('FIELD(group_id,'.$order_array.')')->get()->where('socmed_status',1)->groupBy('group_id');
            foreach($myData as $group => $realdata){
                $index=0;
                foreach($realdata as $v){
                $myData[$group][$index]["detail_data"] = $this->growth_summary($v->id);
                $index++;
                }
            }
            $data["detail"] = $myData;
        }catch(Exception $e){
            $data["detail"] = "";
        }
        
        try{
        $list = GroupMaster::whereIn('id',$group_list)->where('group_type','alexa')->where('group_status','1')->orderBy('group_order','asc')->get()->pluck('id')->toArray();
        $order_array = implode(",",$list);
        $myAlexa = AlexaMaster::whereIn('group_id',$list)->with(['groupMaster'])->orderByRaw('FIELD(group_id,'.$order_array.')')->get()->groupBy('group_id');
        foreach($myAlexa as $group =>$realdata){
        $index=0;
        foreach($realdata as $v){
            $myAlexa[$group][$index]["detail_data"]= $this->growth_summaryAlexa($v->id);
            // dump($myAlexa[$index]->detail_data["rank_now"]);
            $index++;
            }
        }
        $data["alexa"] = $myAlexa;
        }catch(Exception $e){
            $data["alexa"] = "";
        }
        Mail::send('email.report', $data, function($message) use ($data)
        {
          $message->from($data['email']);
          $message->to($data["to"]);
          $message->subject($data['subject']);
        });
        
    }

    private function growth_summary($id){
        $now = SocmedMaster::with(["withSocmedRetrieved" => function($q){
             $q->select(['socmed_id','socmed_total'])->whereraw('cast(created_at as date)=cast(now() as date)');
         }])->where('id',$id)->get();
 
       $yesterday = SocmedMaster::with(["withSocmedRetrieved" => function($q){
         $q->select(['socmed_id','socmed_total'])->whereraw('cast(created_at as date)=cast(NOW() - INTERVAL 1 DAY as date)');
         }])->where('id',$id)->get();

        //  dd($now[0]["withSocmedRetrieved"][0]["socmed_total"]);
             if(count($now[0]["withSocmedRetrieved"]) > 0 && count($yesterday[0]["withSocmedRetrieved"]) > 0){
                 $today_count = $now[0]["withSocmedRetrieved"][0]["socmed_total"] - $yesterday[0]["withSocmedRetrieved"][0]["socmed_total"];
                 $today_percent = ($today_count/$yesterday[0]["withSocmedRetrieved"][0]["socmed_total"])*100;
             }else{
                 $today_count = 0;
                 $today_percent = 0;
             }
             $totalnow = (count($now[0]["withSocmedRetrieved"])>0?$now[0]["withSocmedRetrieved"][0]["socmed_total"]:0);
             $totalyesterday = (count($yesterday[0]["withSocmedRetrieved"])>0?$yesterday[0]["withSocmedRetrieved"][0]["socmed_total"]:0);
   
         $data = ["now"=>rupiah($totalnow),"yesterday"=>rupiah($totalyesterday),"count"=>rupiah($today_count),"percent"=>round($today_percent,3)];
         return $data;
     }




     private function growth_summaryAlexa($id){
        $now = AlexaMaster::with(["withAlexa" => function($q){
             $q->select(['alexa_id','alexa_rank','alexa_local_rank'])->whereraw('cast(created_at as date)=cast(now() as date)');
         }])->where('id',$id)->get();
       $yesterday = AlexaMaster::with(["withAlexa" => function($q){
         $q->select(['alexa_id','alexa_rank','alexa_local_rank'])->whereraw('cast(created_at as date)=cast(now() - INTERVAL 1 DAY as date)');
         }])->where('id',$id)->get();
 
         if(!empty($now[0]["withAlexa"][0]["alexa_rank"]) && !empty($yesterday[0]["withAlexa"][0]["alexa_rank"])){
             $today_rank = $now[0]["withAlexa"][0]["alexa_rank"] - $yesterday[0]["withAlexa"][0]["alexa_rank"];
             $today_local_rank = $now[0]["withAlexa"][0]["alexa_local_rank"] - $yesterday[0]["withAlexa"][0]["alexa_local_rank"];
             $today_rank_percent = ($today_rank/$yesterday[0]["withAlexa"][0]["alexa_rank"])*100;
             $today_local_rank_percent = ($today_local_rank/$yesterday[0]["withAlexa"][0]["alexa_local_rank"])*100;
         }else{
             $today_rank = 0;
             $today_local_rank = 0;
             $today_rank_percent = 0;
             $today_local_rank_percent = 0;
         }
         $rank_now = (count($now[0]["withAlexa"])>0?$now[0]["withAlexa"][0]["alexa_rank"]:0);
         $rank_yesterday = (count($yesterday[0]["withAlexa"])>0?$yesterday[0]["withAlexa"][0]["alexa_rank"]:0);
         $local_now = (count($now[0]["withAlexa"])>0?$now[0]["withAlexa"][0]["alexa_local_rank"]:0);
         $local_yesterday = (count($yesterday[0]["withAlexa"])>0?$yesterday[0]["withAlexa"][0]["alexa_local_rank"]:0);
   
         $data = [ "rank"=>rupiah(switchNumber($today_rank)),
                   "rank_percent"=>round($today_rank_percent,3),
                   "rank_now" => rupiah($rank_now),
                   "rank_yesterday" => rupiah($rank_yesterday),
                   "local_rank"=>rupiah(switchNumber($today_local_rank)),
                   "local_rank_percent"=>round($today_local_rank_percent,3),
                   "local_now" => rupiah($local_now),
                   "local_yesterday" => rupiah($local_yesterday),];
         return $data;
     }
}
