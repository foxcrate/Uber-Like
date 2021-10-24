<?php

namespace App;

use App\Notifications\FleetResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Fleet extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','company','logo','mobile','commercial_register','tax_card','number_tax_card','wallet_balance','id_url'
    ];

    protected $dates = ['deleted_at'];

    public function cars()
    {
        return $this->hasMany('App\Car');
    }

    public function providers()
    {
        return $this->hasMany('App\Provider', 'fleet');
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new FleetResetPassword($token));
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
