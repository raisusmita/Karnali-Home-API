<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FoodItems extends Model
{
    protected $guarded = [];

    public function mainFoodCategory()
    {
        return $this->belongsTo(MainFoodCategory::class);
    }

    public function subFoodCategory()
    {
        return $this->belongsTo(SubFoodCategory::class);
    }

    public function foodHeader()
    {
        return $this->belongsTo(FoodHeader::class);
    }

    public function foodOrderList()
    {
        return $this->belongsTo(FoodOrderList::class);
    }
}
