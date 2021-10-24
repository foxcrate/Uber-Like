<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
    //
    protected $table = 'governorates';
	public $timestamps = true;
	protected $fillable = array('name', 'name_en');

	public function cities()
	{
		return $this->hasMany('App\City');
	}

    public function providers()
    {
        return $this->hasMany('App\Provider');
    }
    public function request_cars()
    {
        return $this->hasMany('App\Request_car');
    }
}
