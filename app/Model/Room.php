<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $guarded =[];

    public function roomCategory()
    {
        return $this->belongsTo(RoomCategory::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

}
