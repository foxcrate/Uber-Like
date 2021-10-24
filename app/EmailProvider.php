<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailProvider extends Model
{
    //
    protected $fillable = [
        'provider_id',
        'email',
    ];

    public function provider()
    {
        return $this->belongsTo('App\Provider');
    }
}
