<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('food_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('food_id');
            $table->unsignedBigInteger('reservation_id');
            $table->unsignedBigInteger('table_id');
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->foreign('food_id')->references('id')->on('foods');
            $table->foreign('reservation_id')->references('id')->on('reservations');
            $table->foreign('table_id')->references('id')->on('tables');
            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->integer('quantity');
            $table->decimal('price',25, 2);
            $table->decimal('total_amount', 50,2);
            $table->dateTime('order_date');
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
        Schema::dropIfExists('food_orders');
    }
}
