<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'email', 'phone', 'customer_type'
    ];

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

}
