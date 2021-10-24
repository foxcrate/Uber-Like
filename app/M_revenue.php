<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M_revenue extends Model
{
    //Revenue
    protected $table = 'm_revenues';
    protected $fillable = [
        'id',
       
        'revenues_sub',
        'revenues_ri',
        'history',
    ];

   
}
