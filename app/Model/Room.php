<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $guarded = [];

    public function roomCategory()
    {
        return $this->belongsTo(RoomCategory::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function foodOrderLists()
    {
        return $this->hasMany(FoodOrderList::class);
    }


    public function roomAvailabilities()
    {
        return $this->hasMany(RoomAvailability::class);
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_availabilities');
    }
}
