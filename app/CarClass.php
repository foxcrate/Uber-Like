<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarClass extends Model
{

    protected $fillable = [
        'name',
        'name_en',
        'logo'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
