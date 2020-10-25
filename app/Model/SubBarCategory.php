<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SubBarCategory extends Model
{
    //
    protected $guarded = [];

    public function mainBarCategory()
    {
        return $this->belongsTo(MainBarCategory::class);
    }

    public function barItems()
    {
        return $this->hasMany(BarItems::class);
    }
}
