<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $guarded =[];

    public function rooms()
    {
        return $this->belongsTo(Room::class);
    }
}
