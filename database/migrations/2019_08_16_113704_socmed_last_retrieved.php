<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SocmedLastRetrieved extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('last_retrieved', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('socmed_id');
            $table->integer('socmed_total');
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
        Schema::dropIfExists('socmed_last_retrieved');
    }
}
