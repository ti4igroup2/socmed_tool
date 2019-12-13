<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GroupMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('group_master', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('group_name',50);
            $table->enum('group_type', ['socmed', 'alexa']);
            $table->enum('group_status', ['0', '1']);
            $table->integer('group_order');
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
        Schema::dropIfExists('group_master');
    }
}
