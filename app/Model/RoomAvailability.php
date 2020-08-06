<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;


class RoomAvailability extends Model
{
    //
    protected $guarded =[];


    public function scopeAvailable($query)
    {
        return $query->where('availability', '0');
    }

    public function scopeUnavailable($query)
    {
        return $query->where('availability', '1');
    }

    // public function room()
    // {
    //     return $this->belongsTo(Room::class);
    // }

    // public function booking()
    // {
    //     return $this->belongsTo(Booking::class);
    // }

}
