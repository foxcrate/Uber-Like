<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{

/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'name_ar',
        'name_en',
        'is_active',

    ];
    protected $hidden = [
         'created_at', 'updated_at'
    ];





    public function subjects(){
        return $this->hasMany('App\Subject');
    }
}
