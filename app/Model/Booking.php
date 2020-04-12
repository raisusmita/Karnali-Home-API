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

    public function setCheckInDateAttribute($value)
    {
        $this->attributes['check_in_date'] = date('Y-m-d h:i:s', strtotime(strtolower($value))
    }
}
