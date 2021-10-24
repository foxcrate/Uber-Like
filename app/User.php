<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use SoftDeletes;

    // protected $primaryKey = 'user_id';

    public function findForPassport($identifier)
    {
        return $this->orWhere('email', $identifier)->orWhere('mobile', $identifier)->first();
    }

//    public function getFullNameAttribute()
//    {
//        return $this->first_name . ' ' . $this->last_name . ' ' . $this->mobile;
//    }
    public function userRequests()
    {
        return $this->hasMany('App\UserRequests', 'user_id');
    }

    public function promocodes()
    {
        return $this->belongsToMany('App\Promocode', 'promocode_usages', 'user_id', 'promocode_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'mobile', 'total_trips', 'total_price',
        'picture', 'password', 'device_type', 'device_token',
        'login_by', 'payment_mode', 'social_unique_id', 'device_id',
        'wallet_balance', 'token_ip', 'status', 'latitude', 'longitude','pone_verified_at',
        'stripe_cust_id', 'wallet_balance', 'rating', 'otp', 'code_reset', 'device_mac', 'id_url'
    ];
    protected $dates = ['deleted_at'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at'
    ];

    public function emailUsers()
    {
        return $this->hasMany('App\EmailUser');
    }

    public function mobileUsers()
    {
        return $this->hasMany('App\MobileUser');
    }

    public function itineraries()
    {
        return $this->belongsToMany('App\Itinerary');
    }
    public function Request_cars()
    {
        return $this->hasMany('App\Request_car');
    }
}
