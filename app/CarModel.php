<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'name_en',
        'date',
        'transtype_id',
        'service_id',
        'carclass_id'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];
    public function TransType(){
        return $this->belongsTo('App\TransportationType','transtype_id');
    }
    public function Service(){
        return $this->belongsTo('App\ServiceType','service_id');
    }
    public function CarClass(){
        return $this->belongsTo('App\CarClass','carclass_id');
    }

    public function providerService(){
        return $this->hasMany('App\ProviderService');
    }

    public function cars()
    {
        return $this->hasMany('App\Car');
    }
    public function request_cars()
    {
        return $this->hasMany('App\Request_car');
    }
}
