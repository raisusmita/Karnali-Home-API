<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class RoomAvailability extends Model
{
    //

    public function scopeAvailable($query)
    {
        return $query->where('availability', '0');
    }

    public function scopeUnavailable($query)
    {
        return $query->where('availability', '1');
    }
}
