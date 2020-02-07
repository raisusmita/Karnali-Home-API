<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RoomCategory extends Model
{
    //

    // protected $fillable = ['room_category', 'number_of_room', 'room_price'];
    protected $guarded =[];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

}
