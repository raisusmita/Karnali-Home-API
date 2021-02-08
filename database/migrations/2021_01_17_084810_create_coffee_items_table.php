<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoffeeItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coffee_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('main_coffee_category_id')->nullable();
            $table->foreign('main_coffee_category_id')->references('id')->on('main_coffee_categories');
            $table->string('coffee_name');
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
        Schema::dropIfExists('coffee_items');
    }
}
