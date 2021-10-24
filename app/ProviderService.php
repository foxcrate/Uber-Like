<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProviderService extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_type_id', 'provider_id', 'status','service_type_id','service_number','service_model'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    /**
     * The services that belong to the user.
     */
    public function provider()
    {
        return $this->belongsTo('App\Provider');
    }

    public function service_type()
    {
        return $this->belongsTo('App\ServiceType');
    }

    public function carModel()
    {
        return $this->belongsTo('App\CarModel','car_type_id');
    }

    public function scopeCheckService($query, $provider_id, $service_id)
    {
        return $query->where('provider_id' , $provider_id)->where('car_type_id' , $service_id);
    }

    public function scopeAvailableServiceProvider($query, $service_id)
    {
        return $query->where('car_type_id', $service_id)->where('status', 'active');
    }

    public function scopeAllAvailableServiceProvider($query)
    {
        return $query->whereIn('status', ['active', 'riding']);
    }

    /**
     * @inheritDoc
     */
    public function getQueueableRelations()
    {
        // TODO: Implement getQueueableRelations() method.
    }

    /**
     * @inheritDoc
     */
    public function getQueueableConnection()
    {
        // TODO: Implement getQueueableConnection() method.
    }

    /**
     * @inheritDoc
     */
    public function resolveRouteBinding($value  , $field = NULL)
    {
        // TODO: Implement resolveRouteBinding() method.
    }
}
