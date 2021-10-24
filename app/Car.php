<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'car_code', 'car_number', 'fleet_id', 'car_model_id', 'color','car_front',
        'car_back', 'car_left', 'car_right', 'car_licence_front', 'car_licence_back',
    ];

    protected $appends = ['provider_list'];

    public function getProviderListAttribute()
    {
        return $this->providers()->pluck( 'providers.id')->toArray();
    }

    public function providers()
    {
        return $this->belongsToMany('App\Provider', 'car_provider')->withPivot('status');
    }

    public function fleet()
    {
        return $this->belongsTo('App\Fleet');
    }
    public function carModel()
    {
        return $this->belongsTo('App\CarModel');
    }

    public function itineraries()
    {
        return $this->hasMany('App\Itinerary');
    }

}
