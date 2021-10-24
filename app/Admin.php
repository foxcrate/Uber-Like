<?php

namespace App;

use App\Notifications\AdminResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class Admin extends Authenticatable
{
    use Notifiable;
//    use HasPermissions;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    // protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'email', 'password', 'picture',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','email_password',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPassword($token));
    }


    protected $appends = ['roles_list'];

    public function getRolesListAttribute()
    {
        return $this->roles()->pluck( 'id')->toArray();
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
    public function resolveRouteBinding($value , $field = NULL)
    {
        // TODO: Implement resolveRouteBinding() method.
    }
}
