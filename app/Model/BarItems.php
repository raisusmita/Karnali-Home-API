<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BarItems extends Model
{
    //
    protected $guarded = [];

    public function mainBarCategory()
    {
        return $this->belongsTo(MainBarCategory::class);
    }

    public function barOrderList()
    {
        return $this->belongsTo(BarOrderList::class);
    }
}
