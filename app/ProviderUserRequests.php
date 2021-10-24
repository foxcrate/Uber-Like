<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProviderUserRequests extends Model
{
    //
    protected $table = 'provider_user_requests';
    protected $fillable = [
        'provider_id',
        'user_request_id',
        'cancel_reason',
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
