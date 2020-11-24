<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubBarCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_bar_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('main_bar_category_id');
            $table->foreign('main_bar_category_id')->references('id')->on('main_bar_categories');
            $table->string('sub_bar_name');
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
        Schema::dropIfExists('sub_bar_categories');
    }
}
