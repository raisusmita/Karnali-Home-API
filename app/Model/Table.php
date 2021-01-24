<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $guarded = [];

    public function foodOrderLists()
    {
        return $this->hasMany(FoodOrderList::class);
    }

    public function barOrderLists()
    {
        return $this->hasMany(BarOrderList::class);
    }

    public function coffeeOrderLists()
    {
        return $this->hasMany(CoffeeOrderList::class);
    }
}
