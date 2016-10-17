<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('activation_code', 255);
            $table->integer('package_type_id')->index();
            $table->integer('created_by')->index();
            $table->boolean('is_used')->default(false);
            $table->dateTime('datetime_used')->nullable();
            $table->integer('used_by_ibo_id')->index();
            $table->integer('encoded_by_ibo_id')->index();
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
        Schema::dropIfExists('packages');
    }
}
