<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransportationType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'name_en',
        'status',
        'image',
        'capacity',
    ];

    public function itineraries()
    {
        return $this->hasMany('App\Itinerary');
    }

    public function serviceTypes() {
        return $this->hasMany('App\ServiceType');
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
