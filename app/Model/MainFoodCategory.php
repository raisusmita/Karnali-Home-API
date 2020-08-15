<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;


class MainFoodCategory extends Model
{
    //
    public function subFoodCategories()
    {
        return $this->hasMany(SubFoodCategory::class);
    }

    public function foodItems()
    {
        return $this->hasMany(FoodItems::class);
    }
}
