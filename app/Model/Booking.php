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

    public function roomCategory()
    {
        return $this->belongsTo(RoomCategory::class);
    }


    public function setCheckInDateAttribute( $pass ) {
    
        $this->attributes['check_in_date'] = date('Y-m-d h:i:s', strtotime(request()->check_in_date));
    
    }

    
    public function setCheckOutDateAttribute( $pass ) {
    
        $this->attributes['check_out_date'] = date('Y-m-d h:i:s', strtotime(request()->check_out_date));
    
    }
}
