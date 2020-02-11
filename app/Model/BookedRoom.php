<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BookedRoom extends Model
{
    protected $guarded =[];

    public function roomCategory()
    {
        return $this->belongsTo(RoomCategory::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
