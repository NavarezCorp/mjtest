<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->index();
            $table->string('code', 255);
            $table->integer('created_by')->index();
            $table->integer('assigned_to_pc_ibo_id')->index()->nullable();
            $table->dateTime('datetime_assigned_to_pc')->nullable();
            $table->integer('assigned_to_pc_creator')->index()->nullable();
            $table->integer('assigned_to_ms_ibo_id')->index()->nullable();
            $table->dateTime('datetime_assigned_to_ms')->nullable();
            $table->integer('assigned_to_ms_creator')->index()->nullable();
            $table->integer('assigned_to_dealer_ibo_id')->index()->nullable();
            $table->dateTime('datetime_assigned_to_dealer')->nullable();
            $table->integer('assigned_to_dealer_creator')->index()->nullable();
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
        Schema::dropIfExists('product_codes');
    }
}
