<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarProvider extends Model
{
    //
    protected $table = 'car_provider';
    protected $fillable = [
        'car_id',
        'provider_id',
        'status',
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
