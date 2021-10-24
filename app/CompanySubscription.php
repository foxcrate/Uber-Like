<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanySubscription extends Model
{
    //
    protected $table = 'company_subscriptions';
    protected $fillable = [
        'fleet_id',
        'money',
        'from',
        'to',
        'status',
    ];

    public function fleet()
    {
        return $this->belongsTo('App\Fleet');
    }
}
