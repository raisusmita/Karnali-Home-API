<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $guarded =[];

    public function foodOrders()
    {
        return $this->hasMany(FoodOrder::class);
    }
    
}
