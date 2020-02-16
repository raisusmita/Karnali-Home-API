<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookedRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booked_rooms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('room_category_id');
            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->foreign('room_category_id')->references('id')->on('room_categories');
            $table->integer('number_of_rooms'); 
            $table->enum('availability', array(0,1)); 
            $table->enum('status', array('booked', 'cancelled', 'reserved')); 
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
        Schema::dropIfExists('booked_rooms');
    }
}
