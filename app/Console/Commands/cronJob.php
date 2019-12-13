<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \GuzzleHttp\Client;

class cronJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socmed:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Running Cron Job';

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
        $req = new Client();
        $base_url = "http://203.12.20.195/rnd-support/sosmed-tool/public";
        $urlyoutube = $base_url."/cron/youtube";
        $urlfb = $base_url."/cron/facebook";
        $urlig = $base_url."/cron/instagram";
        $urltwit = $base_url."/cron/twitter";

        $response = json_decode($req->get($urlyoutube)->getBody()->getContents());
        $response = json_decode($req->get($urlfb)->getBody()->getContents());
        $response = json_decode($req->get($urlig)->getBody()->getContents());
        $response = json_decode($req->get($urltwit)->getBody()->getContents());

    }
}
