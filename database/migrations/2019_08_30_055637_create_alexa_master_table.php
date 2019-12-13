<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlexaMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alexa_master', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('alexa_name');
            $table->string('alexa_url');
            $table->string('alexa_group');
            $table->integer('group_id');
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
        Schema::dropIfExists('alexa_master');
    }
}
