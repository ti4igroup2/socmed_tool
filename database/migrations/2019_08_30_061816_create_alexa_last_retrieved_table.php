<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlexaLastRetrievedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alexa_last_retrieved', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('alexa_id');
            $table->integer('alexa_rank');
            $table->integer('alexa_local_rank');
            $table->string('alexa_locale_code')->nullable();
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
        Schema::dropIfExists('alexa_last_retrieved');
    }
}
