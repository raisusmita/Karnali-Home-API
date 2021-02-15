<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarOrderListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bar_order_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('food_order_id');
            $table->foreign('food_order_id')->references('id')->on('food_orders')->onDelete('cascade');
            $table->unsignedBigInteger('bar_items_id');
            $table->foreign('bar_items_id')->references('id')->on('bar_items');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->unsignedBigInteger('table_id')->nullable();
            $table->foreign('table_id')->references('id')->on('tables');
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->integer('quantity');
            $table->enum('order_status', array('ordered', 'processing', 'completed'))->default('ordered');
            $table->enum('status', array('due', 'paid'));
            $table->decimal('price', 25, 2);
            $table->decimal('total_amount', 50, 2);
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
        Schema::dropIfExists('bar_order_lists');
    }
}
