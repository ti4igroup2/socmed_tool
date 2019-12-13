<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SocmedFanspageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('fanspage', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->bigInteger('fanpage_id');
            $table->string('fanpage_name',100);
            $table->string('access_token');
            $table->enum('fanpage_status', ['0', '1']);
            $table->timestamps();
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
        Schema::dropIfExists('fanspage');
    }
}
