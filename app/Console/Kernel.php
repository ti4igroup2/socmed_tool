<?php
namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;
use App\Models\CronEmail;
use App\Models\Fanspage;

class Kernel extends ConsoleKernel
{
    
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {  

        // top post
        $schedule->command("retrieve:post --time='today'")->hourly()->appendOutputTo(storage_path('logs/perjam.log'));
        
        $fplist = Fanspage::where('fanpage_status',"1")->get();
        foreach($fplist as $r){
            $schedule->command("retrieve:post --fpid='".$r->fanpage_id."' --token='".$r->access_token."'")->everyTenMinutes()->appendOutputTo(storage_path('logs/ssepuluhmenit.log'));
        }

        // retrieve subscriber
        $command = ["retrieve:alexa","retrieve:facebook","retrieve:instagram","retrieve:twitter","retrieve:youtube"];
        foreach($command as $c){
            $schedule->command($c)
                    ->dailyAt('06:00');
        }

        // alexa error
        $schedule->command("retrieve:alexa_error")->dailyAt('06:15');
        
        // email report
        $data = CronEmail::where('email_status',"1")->get();     
        foreach($data as $d){
            $cron = explode(',',$d->email_cron);
            if($cron[0]=="everyday"){
                $schedule->command('mail:report --user="" --id="'.$d->id.'"')->dailyAt($cron[1]);
            }else{
                $schedule->command('mail:report --user="" --id="'.$d->id.'"')->weeklyOn($cron[0],$cron[1]);
            }
        }

       
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
