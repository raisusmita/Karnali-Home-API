<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //

    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'email', 'phone',
    ];
}
