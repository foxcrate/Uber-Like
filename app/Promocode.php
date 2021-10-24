<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promocode extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'promocodes';
    public $timestamps = true;
    protected $fillable = [
        'promo_code','discount','expiration'
    ];

    protected $appends = ['users_list'];

    public function getUsersListAttribute()
    {
        return $this->users()->pluck( 'users.id')->toArray();
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'promocode_usages', 'promocode_id', 'user_id');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
