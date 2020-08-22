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

    public function subBarCategory()
    {
        return $this->belongsTo(SubBarCategory::class);
    }
}
