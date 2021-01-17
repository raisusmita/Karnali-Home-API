<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BarOrderList extends Model
{
    //
    protected $guarded = [];

    public function room()
    {
        return $this->belongsTo(Room::class);
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

    public function barItems()
    {
        return $this->belongsTo(BarItems::class);
    }
}
