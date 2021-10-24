<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    //Revenue
    protected $table = 'revenues';
    protected $fillable = [
        'provider_id',
        'money',
        'from',
        'to',
        'status',
    ];

    public function provider()
    {
        return $this->belongsTo('App\Provider');
    }
}
