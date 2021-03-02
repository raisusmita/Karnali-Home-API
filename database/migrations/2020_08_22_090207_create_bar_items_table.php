<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bar_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('main_bar_category_id')->nullable();
            $table->foreign('main_bar_category_id')->references('id')->on('main_bar_categories');
            $table->unsignedBigInteger('bar_name_id')->nullable();
            $table->foreign('bar_name_id')->references('id')->on('bar_names');
            $table->enum('quantity', ['30ML', '60ML', 'QRT', 'HALF', 'FULL', 'GLASS', 'PER PC', 'PACKET'])->nullable();
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
        Schema::dropIfExists('bar_items');
    }
}
