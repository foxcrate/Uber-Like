<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    public function providers()
    {
        return $this->belongsToMany('App\Provider', 'provider_services', 'service_type_id', 'provider_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'name_en',
        'image',
        'price',
        'sub_com',
        'fixed',
        'description',
        'status',
        'minute',
        'distance',
        'calculator',
        'waiting',
        'min_wait_price',
        'transportation_type_id',
    ];

    public function carModel(){
        return $this->hasMany('App\CarModel','service_id');
    }

    public function transportationType() {
        return $this->belongsTo('App\TransportationType','transportation_type_id');
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'created_at', 'updated_at'
    ];
}
