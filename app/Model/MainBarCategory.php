<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MainBarCategory extends Model
{
    //
    protected $guarded = [];

    public function barItems()
    {
        return $this->hasMany(BarItems::class);
    }
}
