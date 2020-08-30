<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MainBarCategory extends Model
{
    //
    protected $guarded = [];

    public function subBarCategories()
    {
        return $this->hasMany(SubBarCategory::class);
    }

    public function barItems()
    {
        return $this->hasMany(BarItems::class);
    }
}
