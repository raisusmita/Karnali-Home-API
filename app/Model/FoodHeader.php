<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FoodHeader extends Model
{
    protected $guarded = [];

    public function foodItems()
    {
        return $this->hasMany(FoodItems::class);
    }
}
