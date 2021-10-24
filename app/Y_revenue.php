<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Y_revenue extends Model
{
    //Revenue
    protected $table = 'y_revenues';
    protected $fillable = [
        'id',
       
        'revenues_sub',
        'revenues_ri',
        'history',
    ];

   
}
