<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $guarded =[];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function room_transaction()
    {
        return $this->hasOne(RoomTransaction::class);
    }

    public function foodOrders()
    {
        return $this->hasMany(FoodOrder::class);
    }

    public function booking(){
        return $this->belongsTo(Booking::class);
    }

    // public function setCheckInDateAttribute( $pass ) {
    
    //     $this->attributes['check_in_date'] = date('Y-m-d h:i:s', strtotime(request()->check_in_date));
    
    // } 

    
    // public function setCheckOutDateAttribute( $pass ) {
    
    //     $this->attributes['check_out_date'] = date('Y-m-d h:i:s', strtotime(request()->check_out_date));
    
    // }
}
