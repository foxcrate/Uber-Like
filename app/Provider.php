<?php

namespace App;

use App\Notifications\ProviderResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Provider extends Authenticatable{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'first_name','last_name','email','password',
        'mobile','address','picture','gender','latitude',
        'longitude','status','social_unique_id','fleet','fleet_id','wallet_balance','car_type','end_id_licence',
        'governorate_id','id_url',
        'avatar','avatar_status',
        'driver_licence_front','driver_licence_front_status',
        'driver_licence_back','driver_licence_back_status',
        'identity_front','identity_front_status',
        'identity_back','identity_back_status',
        'criminal_feat','criminal_feat_status',
        'drug_analysis_licence','drug_analysis_licence_status',

        'rating','device_mac','otp','code_reset','login_by',
        'id_licence','driver_licence','criminal_status_licence','end_driver_licence','car_id',

    ];

    public function serviceTypes()
    {
        return $this->belongsToMany(ServiceType::class, 'provider_services', 'provider_id', 'service_type_id','email');
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'updated_at'
    ];

    protected $dates = ['deleted_at'];

    /**
     * The services that belong to the user.
     */
    public function service()
    {
        return $this->hasOne('App\ProviderService');
    }

    /**
     * The services that belong to the user.
     */
    public function incoming_requests()
    {
        return $this->hasMany('App\RequestFilter')->where('status', 0);
    }
    public function governorate()
    {
        return $this->belongsTo('App\Governorate');
    }

    /**
     * The services that belong to the user.
     */
    public function requests()
    {
        return $this->hasMany('App\RequestFilter');
    }

    public function fleets()
    {
        return $this->belongsTo('App\Fleet', 'fleet');
    }

    /**
     * The services that belong to the user.
     */
    public function profile()
    {
        return $this->hasOne('App\ProviderProfile');
    }

    /**
     * The services that belong to the user.
     */
    public function device()
    {
        return $this->hasOne('App\ProviderDevice');
    }

    /**
     * The services that belong to the user.
     */
    public function trips()
    {
        return $this->hasMany('App\UserRequests');
    }

    public function revenues()
    {
        return $this->hasMany('App\Revenue');
    }

    public function companySubscriptions()
    {
        return $this->hasMany('App\CompanySubscription');
    }

    public function itineraries()
    {
        return $this->hasMany('App\Itinerary');
    }

    /**
     * The services accepted by the provider
     */
    public function accepted()
    {
        return $this->hasMany('App\UserRequests','provider_id')
                    ->where('status','!=','CANCELLED');
    }

    /**
     * service cancelled by provider.
     */
    public function cancelled()
    {
        return $this->hasMany('App\UserRequests','provider_id')
                ->where('status','CANCELLED');
    }

    /**
     * The services that belong to the user.
     */
    public function documents()
    {
        return $this->hasMany('App\ProviderDocument');
    }

    /**
     * The services that belong to the user.
     */
    public function document($id)
    {
        return $this->hasOne('App\ProviderDocument')->where('document_id', $id)->first();
    }

    /**
     * The services that belong to the user.
     */
    public function pending_documents()
    {
        return $this->hasMany('App\ProviderDocument')->where('status', 'ASSESSING')->count();
    }


    public function cars()
    {
        return $this->belongsToMany('App\Car','car_provider')->withPivot('status');
    }

    public function emailProviders()
    {
        return $this->hasMany('App\EmailProvider');
    }

    public function mobileProviders()
    {
        return $this->hasMany('App\MobileProvider');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ProviderResetPassword($token));
    }
}
