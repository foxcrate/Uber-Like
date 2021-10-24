<?php

use App\PromocodeUsage;
use App\ServiceType;

function currency($value = '')
{
	if($value == ""){
		return Setting::get('currency')."0.00";
	} else {
		return Setting::get('currency').$value;
	}
}

function distance($value = '')
{
    if($value == ""){
        return "0".Setting::get('distance', 'Km');
    }else{
        return $value.Setting::get('distance', 'Km');
    }
}

function img($img){
	if($img == ""){
		return asset('main/avatar.jpg');
	}else if (strpos($img, 'http') !== false) {
        return $img;
    }else{
		return asset('storage/'.$img);
	}
}

function image($img){
	if($img == ""){
		return asset('main/avatar.jpg');
	}else{
		return asset($img);
	}
}

function promo_used_count($promo_id)
{
	return PromocodeUsage::where('status','ADDED')->where('promocode_id',$promo_id)->count();
}

function curl($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $return = curl_exec($ch);
    curl_close ($ch);
    return $return;
}

function get_all_service_types()
{
	return ServiceType::all();
}

if(! function_exists('uploadFile')) {
    /**
      * Upload File
      * @param $file ($request->file()).
      * @param $path (Optional default is public/uploads) .
      */
    function uploadFile($file = null , $path = null)
    {
        $path = (isset($path) && $path != null) ? $path : 'uploads'; # check for path ...

        $folder_name = date('y').'/'.date('M').'/'.date('d');

        \File::makeDirectory('/uploads/'.$folder_name, 0775, true, true);

        if ($file->isValid()) {
            $extention = $file->getClientOriginalExtension();
            $file_name = getToken() . '.' . $extention;
            $file->move($path.'/'.$folder_name , $file_name);

            return $folder_name.'/'.$file_name;
        }
        return '';
    }
}
function responseJson($status, $message, $data=null)
{
    $response = [
        'status' => $status,
        'message' => $message,
        'data' => $data,
    ];
    return response()->json($response);
}

if (!function_exists('lang')) {
    function lang() {
        if (session()->has('lang')) {
            return session('lang');
        } else {
            return 'ar';
        }
    }
}