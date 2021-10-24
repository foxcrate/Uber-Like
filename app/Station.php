<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    //
    protected $table = 'stations';
    protected $fillable = [
        'station',
        'substation',
    ];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
