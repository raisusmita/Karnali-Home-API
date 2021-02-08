<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MainCoffeeCategory extends Model
{
    //
    protected $guarded = [];

    public function coffeeItems()
    {
        return $this->hasMany(FoodItems::class);
    }
}
