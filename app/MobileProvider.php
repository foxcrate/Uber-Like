<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MobileProvider extends Model
{
    //
    protected $fillable = [
        'provider_id',
        'mobile',
    ];

    public function provider()
    {
        return $this->belongsTo('App\Provider');
    }
}
