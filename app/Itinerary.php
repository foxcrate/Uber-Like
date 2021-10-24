<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    protected $table = 'itineraries';
    protected $fillable = [
        's_address_ar',
        's_address_en',
        's_latitude',
        's_longitude',
        'd_address_ar',
        'd_address_en',
        'distance',
        'd_latitude',
        'd_longitude',
        'number_station',
        'from_time',
        'to_time',
        'capacity',
        'status',
        'day_trip_time_id',
        'transportation_type_id',
        'provider_id',
        'car_id',
    ];


    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function stations()
    {
        return $this->belongsToMany('App\Station');
    }

    protected $appends = ['user_list', 'station_list'];

    public function getStationListAttribute()
    {
        return $this->stations()->pluck('stations.id')->toArray();
    }
    public function getUserListAttribute()
    {
        return $this->users()->pluck('users.id')->toArray();
    }

    public function dayTripTime()
    {
        return $this->belongsTo('App\DayTripTime');
    }

    public function transportationType()
    {
        return $this->belongsTo('App\TransportationType');
    }

    public function provider()
    {
        return $this->belongsTo('App\Provider');
    }

    public function car()
    {
        return $this->belongsTo('App\Car');
    }

    public function userRequests()
    {
        return $this->hasMany('App\UserRequests');
    }
}
