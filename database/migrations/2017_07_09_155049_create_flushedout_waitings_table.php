<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlushedoutWaitingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flushedout_waitings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ibo_id')->index();
            $table->text('waitings_left');
            $table->text('waitings_right');
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
        Schema::dropIfExists('flushedout_waitings');
    }
}
