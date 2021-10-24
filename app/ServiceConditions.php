<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceConditions extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table ='service_conditions';
    protected $fillable = [
        'id',
        'title',
        'title_en',
        'details',
        'details_en',
        'status'
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
