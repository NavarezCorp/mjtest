<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIbosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ibos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname', 255);
            $table->string('middlename', 255);
            $table->string('lastname', 255);
            $table->boolean('is_part_company');
            $table->integer('sponsor_id')->index();
            $table->integer('placement_id')->index();
            $table->string('placement_position', 1);
            $table->decimal('total_purchase_amount', 9, 2)->nullable();
            $table->integer('ranking_lions_id')->nullable()->index();
            $table->boolean('is_admin');
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
        Schema::dropIfExists('ibos');
    }
}
