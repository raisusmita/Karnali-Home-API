<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded =[];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function room_transactions()
    {
        return $this->hasMany(RoomTransaction::class);
    }

    public function roomAvailabilityBooking()
    {
        return $this->hasOneThrough(RoomAvailability::class, Booking::class,
        'customer_id', // Foreign key on booking table...
        'booking_id', // Foreign key on room_availabilities table...
        'id', // Local key on customer table...
        'id' // Local key on booking table...
        );
    }

}
