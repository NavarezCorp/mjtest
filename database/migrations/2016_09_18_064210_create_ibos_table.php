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
            $table->string('middlename', 255)->nullable();
            $table->string('lastname', 255);
            $table->boolean('is_part_company')->default(false);
            $table->integer('sponsor_id')->index();
            $table->integer('placement_id')->index();
            $table->string('placement_position', 1);
            $table->decimal('total_purchase_amount', 9, 2)->nullable();
            $table->integer('ranking_lions_id')->nullable()->index();
            $table->boolean('is_admin')->default(false);
            $table->string('activation_code', 255)->nullable();
            $table->string('activation_code_type', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->integer('gender_id')->index();
            $table->string('birth_date', 255);
            $table->integer('marital_status_id')->index()->nullable();
            $table->string('tin', 255)->nullable();
            $table->string('sss', 255)->nullable();
            $table->string('address', 255);
            $table->string('city', 255);
            $table->string('province', 255);
            $table->string('contact_no', 255)->nullable();
            $table->integer('pickup_center_id')->index();
            $table->integer('bank_id')->index()->nullable();
            $table->string('account_no', 255)->nullable();
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
