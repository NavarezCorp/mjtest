<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommissionRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sponsor_id')->index();
            $table->integer('commission_type_id')->index();
            $table->integer('from_ibo_id')->index();
            $table->decimal('commission_amount', 9, 2);
            $table->integer('created_by')->index();
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
        Schema::dropIfExists('commission_records');
    }
}
