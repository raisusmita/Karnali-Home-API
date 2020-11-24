<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubFoodCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_food_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('main_food_category_id');
            $table->foreign('main_food_category_id')->references('id')->on('main_food_categories');
            $table->string('sub_food_name');
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
        Schema::dropIfExists('sub_food_categories');
    }
}
