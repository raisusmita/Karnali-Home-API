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

}
