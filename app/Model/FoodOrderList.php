<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FoodOrderList extends Model
{
    protected $guarded = [];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function foodOrder()
    {
        return $this->belongsTo(FoodOrder::class);
    }

    public function foodItems()
    {
        return $this->belongsTo(FoodItems::class);
    }

    public function coffeeItems()
    {
        return $this->belongsTo(CoffeeItems::class);
    }

    public function barItems()
    {
        return $this->belongsTo(BarItems::class);
    }
}
