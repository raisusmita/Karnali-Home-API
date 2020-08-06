<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    //
    protected $guarded =[];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // public function roomAvailability()
    // {
    //     return $this->hasMany(RoomAvailability::class);
    // }

    public function rooms()
{
    return $this->belongsToMany(Room::class, 'room_availabilities');
}

    public function roomCategory()
    {
        return $this->belongsTo(RoomCategory::class);
    }

    //Formatting invalid date for booking
    public function setCheckInDateAttribute( $pass ) {
    
        $this->attributes['check_in_date'] = date('Y-m-d h:i:s', strtotime(request()->check_in_date));
    
    } 

    
    public function setCheckOutDateAttribute( $pass ) {
    
        $this->attributes['check_out_date'] = date('Y-m-d h:i:s', strtotime(request()->check_out_date));
    
    }
}
