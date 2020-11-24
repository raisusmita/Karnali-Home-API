<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SubFoodCategory extends Model
{
    protected $guarded = [];

    public function mainFoodCategory()
    {
        return $this->belongsTo(MainFoodCategory::class);
    }

    public function foodItems()
    {
        return $this->hasMany(FoodItems::class);
    }
}
