<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SocmedPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('post', function (Blueprint $table) {
            $table->string('id');
            $table->bigInteger('fanpage_id');
            $table->string('message',150);
            $table->string('full_picture');
            $table->string('created_time');
            $table->string('admin_creator',60);
            $table->integer('shares');
            $table->integer('likes');
            $table->integer('comments');
            $table->integer('love');
            $table->integer('wow');
            $table->integer('haha');
            $table->integer('sad');
            $table->integer('angry');
            $table->string('after');
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
        Schema::dropIfExists('post');
    }
}
