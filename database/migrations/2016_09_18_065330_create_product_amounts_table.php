<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_amounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->index();
            $table->decimal('amount', 9, 2);
            $table->boolean('is_active')->default(false);
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
        Schema::dropIfExists('product_amounts');
    }
}
