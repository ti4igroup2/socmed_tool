<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CronEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('cron_email', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email_subject',50);
            $table->string('email_recipient');
            $table->string('email_cron');
            $table->string('email_grouplist');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('cron_email');
    }
}
