<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RoomTransaction extends Model
{
    protected $guarded =[];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    
}
