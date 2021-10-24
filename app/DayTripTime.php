<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DayTripTime extends Model
{
    protected $table = 'day_trip_times';
    protected $fillable = [
        'day',
        'period',
        'from',
        'to',
        'status',
    ];

    public function itineraries()
    {
        return $this->hasMany('App\Itinerary');
    }
}
