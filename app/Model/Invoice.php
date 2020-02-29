<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $guarded =[];

    public function foodOrders()
    {
        return $this->hasMany(FoodOrder::class);
    }

    public function roomTransactions()
    {
        return $this->hasMany(RoomTransaction::class);
    }
    
}
