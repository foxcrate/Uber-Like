<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRequestPayment extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'status', 'password', 'remember_token', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_id',
        'payment_mode',
        'promocode_id',
        'fixed',
        'distance',
        'commision',
        'discount',
        'tax',
        'wallet',
        'discount_wallet',
        'surge',
        'time_trip',
        'time_trip_price',
        'min_wait_price',
        'price',
        'WaitingTime',
        'WaitingPrice',
        'provider_money',
        'total',
    ];

    /**
     * The services that belong to the user.
     */
    public function request()
    {
        return $this->belongsTo('App\UserRequests');
    }

    /**
     * The services that belong to the user.
     */
    public function provider()
    {
        return $this->belongsTo('App\Provider');
    }
}
