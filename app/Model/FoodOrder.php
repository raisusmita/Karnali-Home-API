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
}
