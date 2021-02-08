<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FoodOrder extends Model
{
    //
    protected $guarded = [];

    public function foodOrderLists()
    {
        return $this->hasMany(FoodOrderList::class);
    }

    public function foodItems()
    {
        return $this->belongsTo(FoodItems::class);
    }

    public function barOrderLists()
    {
        return $this->hasMany(BarOrderList::class);
    }

    public function barItems()
    {
        return $this->belongsTo(BarItems::class);
    }

    public function coffeeOrderLists()
    {
        return $this->hasMany(CoffeeOrderList::class);
    }

    public function coffeeItems()
    {
        return $this->belongsTo(CoffeeItems::class);
    }
}
