<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MobileUser extends Model
{
    //
    protected $fillable = [
        'user_id',
        'mobile',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
