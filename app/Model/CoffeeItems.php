<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CoffeeItems extends Model
{
    //
    protected $guarded = [];
    public function mainCoffeeCategory()
    {
        return $this->belongsTo(MainCoffeeCategory::class);
    }
}
