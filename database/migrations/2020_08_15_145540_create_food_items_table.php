<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('main_food_category_id')->nullable();
            $table->foreign('main_food_category_id')->references('id')->on('main_food_categories');
            $table->unsignedBigInteger('sub_food_category_id')->nullable();
            $table->foreign('sub_food_category_id')->references('id')->on('sub_food_categories');
            $table->string('food_name');
            $table->double('price');
            $table->text('details')->nullable();
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
        Schema::dropIfExists('food_items');
    }
}
