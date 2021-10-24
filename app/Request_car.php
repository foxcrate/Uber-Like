<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Request_car extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_borr',
        'id_models',
        'id_governorate',
        'number_seats',
        'full_type',
        'gearbox',
        'color',
        'from',
        'to',
        'note_ar',
        'note_en',
        'price',
        'photo1',
        'photo2',
        'photo3',
        'photo4',
        'year_date',
        'status',
        'lat',
        'long',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function user()
    {
        return $this->belongsTo('App\User','id_borr');
    }
    public function model()
    {
        return $this->belongsTo('App\CarModel','id_models');
    }
    public function governorate()
    {
        return $this->belongsTo('App\Governorate','id_governorate');
    }

}
