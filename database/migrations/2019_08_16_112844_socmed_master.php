<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SocmedMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('master', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('socmed_name');
            $table->string('socmed_url');
            $table->enum('socmed_type', ['facebook', 'twitter','instagram','youtube']);
            $table->string('socmed_group');
            $table->integer('group_id');
            $table->enum('socmed_status', ['0', '1']);
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
        Schema::dropIfExists('socmed_master');
    }
}
