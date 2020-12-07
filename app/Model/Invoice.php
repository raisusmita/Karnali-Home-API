<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $guarded = [];

    public function foodOrderLists()
    {
        return $this->hasMany(FoodOrderList::class);
    }

    public function roomTransactions()
    {
        return $this->hasMany(RoomTransaction::class);
    }
}
