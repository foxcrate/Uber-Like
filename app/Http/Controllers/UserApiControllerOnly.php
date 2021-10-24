<?php

namespace App\Http\Controllers;

use App\Car;
use App\CompanySubscription;
use App\EmailUser;
use App\Mail\ResetPasswordUser;
use App\MobileUser;
use App\Revenue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Input;
use Laravel\Passport\Client;
use Log;
use JWTAuth;
//use Hash;
use Storage;
use Setting;
use Exception;

use Carbon\Carbon;
use App\Http\Controllers\SendPushNotification;
use App\Notifications\ResetPasswordOTP;
use App\Helpers\Helper;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\Card;
use App\User;
use Config;
use App\Provider;
use App\CarModel;
use App\Governorate;
use App\Settings;
use App\Promocode;
use App\ServiceType;
use App\UserRequests;
use App\RequestFilter;
use App\PromocodeUsage;
use App\ProviderService;
use App\UserRequestRating;
use App\Http\Controllers\ProviderResources\TripController;


class UserApiControllerOnly extends Controller
{

    public function __construct()
    {
        $this->client = Client::find(1);
    }

//    public function __construct()
//    {
//        $this->middleware('api'); //, ['except' => ['show', 'store', 'available', 'location_edit', 'location_update', 'checkAccount']]
//    }

    public function get_andriod_version(){
        $a=Settings::find(110);
        $version=$a->value;
        $b=Settings::find(109);
        $importance=(int)$b->value;
        $gift_percentage=Settings::find(113);
        //return $gift_percentage;
        if($gift_percentage->value != 0){
            $gift=Settings::find(114);
        }else{
            $gift=null;
        }
        
        $x=[
            'version'=>$version,
            'importance'=>$importance,
            'gift'=>$gift
        ];
        return responseJson(200, "Done", $x);
    }

    public function socket(){
        //return "Alo";
        
        try{

            $response = Http::post('http://192.168.1.220:3000/client_out', [
            //$response = Http::post('http://192.168.1.220/ailbaz_serverX/ailbaz_server/public/api/test2', [
                'id' => 11,
            ]);
          
            //$resp = Http::get('http://192.168.1.220:3000/alo');
            return $response;


        }catch(Throwable $e){
            return $e;
        }
        //return Done;

    }

    public function test2(Request $req){
        //return "Alo";
        return $req;
    }

    public function governments(){
        $governments=Governorate::where('available','=',1)->get();

        return  $governments;

        //return responseJson(200, trans('admin.The operation was successful'), $governments);

    }

    public function searchingUser(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'device_mac' => 'required',
        ]);
        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }
//dd($request->user()->id);
        if (auth()->user()->device_mac == $request->device_mac) {
            //$trip = UserRequests::where('user_id', $request->user()->id)->where('status', '!=', ['SEARCHING', 'COMPLETED', 'SCHEDULED', 'CANCELLED'])->get();
            $trip = UserRequests::where('user_id', $request->user()->id)->where('status', '!=', ['SEARCHING'])->where('status', '!=', ['COMPLETED'])->where('status', '!=', ['SCHEDULED'])->where('status', '!=', ['CANCELLED'])->get();
            //$trip=collect($trip)->sortBy('created_at')->toArray();
            //$trip = UserRequests::where('user_id', $request->user()->id)->where('status', '!=', ['COMPLETED', 'CANCELLED'])->get();

            //return [$trip[0]['provider_id']];
            $providerID=$trip[0]['provider_id'];
            $provider=Provider::find($providerID);
            //return $provider;
            $carID=$provider['car_id'];
            //return [$provider['car_id']];
            $car=Car::find($carID);
            //return $car;
            $serviceID=$trip[0]['service_type_id'];
            $service=ServiceType::find($serviceID);
            $modelID=$car->car_model_id;
            $model=CarModel::find($modelID);

            if(count($trip) == 0){
                return ["msg"=>'alo'];
            }
            elseif (count($trip) > 0) {
               //return responseJson(200, trans('admin.The operation was successful'),[ $trip->load('provider'),$car]);
               return responseJson(200, trans('admin.The operation was successful'), [
                   'trip_created_at'=>$trip[0]->updated_at,
                   'tripID'=>$trip[0]->id,
                   'tripStatus'=>$trip[0]->status,
                   'clientID'=>$trip[0]->user_id,
                   'providerID'=>$provider->id,
                   'providerFirstName'=>$provider->first_name,
                   'providerLastName'=>$provider->last_name,
                   'providerEmail'=>$provider->email,
                   'providerMobile'=>$provider->mobile,
                   'providerRating'=>$provider->rating,
                   'providerAvatar'=>$provider->avatar,
                   'carID'=>$car->id,
                    'carModelAR'=>$model->name,
                    'carModelEN'=>$model->name_en,
                    'carColor'=>$car->color,
                    'carLeft'=>$car->car_left,
                    'carRight'=>$car->car_right,
                    'carNumber'=>$car->car_number,
                    'serviceTypeAR'=>$service->name,
                    'serviceTypeEN'=>$service->name_en,
                    ]);
            }

             else {
                return responseJson(422, trans('admin.Something Went Wrong'));
            }
        } else {
            return response()->json(['error' => 'api.no'], 401);
        }
    }


    public
    function scheduleSearching(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'id' => 'required|exists:user_requests,id',
        ]);
        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }

        $trip = UserRequests::find($request->id);
        if ($trip) {
            if ($trip->status == 'SCHEDULED') {
                $trips = $trip->update([
                    'status' => 'SEARCHING',// وافق
                ]);
                return responseJson(1, lang('admin.start searching'), $trip->load('user'));
            } else {
                return responseJson(0, lang('admin.start searching'));
            }

        }
    }


    public function rateUser(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'trip_id' => 'required|exists:user_requests,id',
            'rating' => 'required|in:1,2,3,4,5',
            'comment' => 'nullable',
        ]);

        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }


        //return $num_of_requests;

        $user_requests = UserRequests::findOrFail($request->trip_id); //trip
        $provider_id = $user_requests->provider_id;
        $num_of_requests = UserRequests::where('provider_id', Auth::user()->id)->where('status' ,'=' ,'COMPLETED')->count();
        $provider=Provider::find($provider_id);

        $user = $request->user(); // provider auth
        if ($user_requests->status == 'COMPLETED') {
            if ($user->id == $user_requests->user_id) {
                if ($user_requests->rating == null) {
                    $rate = UserRequestRating::create([
                        'user_comment' => $request->comment,
                        'user_rating' => $request->rating,
                    ]);
                    $rate->request_id = $request->trip_id;
                    $rate->user_id = $user->id;
                    $rate->provider_id = $user_requests->provider_id;
                    $rate->save();

                    $user=User::find(Auth::user()->id);
                    $provider->rating= ( ($provider->rating * $num_of_requests) + (1 * $request->rating) ) / ($num_of_requests + 1) ;
                    $provider->save();
                    //return $user->rating;

                    return responseJson(1, 'تمت العمليه بنجاح', $rate);
                } else {
                    $rate = $user_requests->rating->update([
                        'user_comment' => $request->comment,
                        'user_rating' => $request->rating,
                    ]);

                    //return $num_of_requests;
                    //return ( ($user->rating * $num_of_requests) + (1 * $request->rating) ) / ($num_of_requests + 1) ;
                    $user=User::find(Auth::user()->id);
                    $provider->rating= ( ($provider->rating * $num_of_requests) + (1 * $request->rating) ) / ($num_of_requests + 1)  ;
                    $provider->save();
                    //return $user->rating;


                    return responseJson(1, 'تمت العمليه بنجاح', $rate);
                }
            } else {
                return responseJson(0, 'لا يمكنك التعليق على الرحله');
            }
        } else {
            return responseJson(0, 'هذا الطلب لم يكتمل بعد');
        }
    }

    public
    function providerAll(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'service' => 'numeric|exists:service_types,id'
        ]);

        $provider = ServiceType::find($request->service)->providers()->get();

        $response = [
            'data' => $provider,
        ];
        return response()->json($response);
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function authenticate2(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'device_id' => 'required',
            'device_type' => 'required|in:android,ios',
            'device_token' => 'required',
            'device_mac' => 'required',
            'username' => 'required',
            'password' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }
        //return $request;
        $user=User::where("mobile","=",$request->username)->get()->first();
        //return $user->device_mac;
        if($user->device_mac == "dummy" ||$user->device_mac ==$request->device_mac ){


            $Useremail = User::where(['email' => $request->username])->orWhere(['mobile' => $request->username])->first()->email;
            $request['email'] = $Useremail;
            Config::set('auth.providers.users.model', 'App\User');
            $credentials = $request->only('email', 'password');

            try {
                if (!$token = JWTAuth::attempt($credentials)) {
                    return response()->json(['message' => 'The mobile address or password you entered is incorrect.'], 401);
                }
            } catch (JWTException $e) {
                return response()->json(['message' => 'Something went wrong, Please try again later!'], 500);
            }
            $User = User::find(Auth::user()->id);
            $User->update([
                'status' => 'online',
                'device_id' => $request->device_id,
                'device_type' => $request->device_type,
                'device_token' => $request->device_token,
                'device_mac' => $request->device_mac,
            ]);

            $params = [
                'grant_type' => 'password',
                'client_id' => $this->client->id,
                'client_secret' => $this->client->secret,
                'username' => request('email'),
                'password' => request('password'),
                'scope' => '*',
            ];
            $request->request->add($params);

            $proxy = Request::create('oauth/token', 'POST');

            $obj=Route::dispatch($proxy);
            //return $obj;
            // $token_type=$obj["token_type"];
            // return $token_type;
            return Route::dispatch($proxy);
            //return responseJson(0, 'بيانات الدخول غير صحيحه',['aaa' => Route::dispatch($proxy), 'users' => $User]);
        }
        else if($user->device_mac != $request->device_mac){
            return responseJson(666, "Wrong Mac");
        }
    }

    public function authenticate(Request $request){
        $validator = validator()->make($request->all(), [
            'device_id' => 'required',
            'device_type' => 'required|in:android,ios',
            'device_token' => 'required',
            'device_mac' => 'required',
            'username' => 'required',
            'password' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }
        //return $request;
        $user=User::where("mobile","=",$request->username)->get()->first();
        //return $user->device_mac;

        $Useremail = User::where(['email' => $request->username])->orWhere(['mobile' => $request->username])->first()->email;
            $request['email'] = $Useremail;
            Config::set('auth.providers.users.model', 'App\User');
            $credentials = $request->only('email', 'password');

            try {
                if (!$token = JWTAuth::attempt($credentials)) {
                    return response()->json(['message' => 'The mobile address or password you entered is incorrect.'], 401);
                }
                else if($token = JWTAuth::attempt($credentials)){

                    if($user->device_mac == "dummy" ||$user->device_mac ==$request->device_mac ){


                        $User = User::find(Auth::user()->id);
                        $User->update([
                            'status' => 'online',
                            'device_id' => $request->device_id,
                            'device_type' => $request->device_type,
                            'device_token' => $request->device_token,
                            'device_mac' => $request->device_mac,
                        ]);

                        $params = [
                            'grant_type' => 'password',
                            'client_id' => $this->client->id,
                            'client_secret' => $this->client->secret,
                            'username' => request('email'),
                            'password' => request('password'),
                            'scope' => '*',
                        ];
                        $request->request->add($params);

                        $proxy = Request::create('oauth/token', 'POST');

                        $obj=Route::dispatch($proxy);
                        //return $obj;
                        // $token_type=$obj["token_type"];
                        // return $token_type;
                        return Route::dispatch($proxy);
                        //return responseJson(0, 'بيانات الدخول غير صحيحه',['aaa' => Route::dispatch($proxy), 'users' => $User]);
                    }
                    else if($user->device_mac != $request->device_mac){
                        return responseJson(666, "Wrong Mac");
                    }

                }
            } catch (JWTException $e) {
                return response()->json(['message' => 'Something went wrong, Please try again later!'], 500);
            }

    }

//    public function authenticate(Request $request)
//    {
//        $validator = validator()->make($request->all(), [
//            //'lang' => 'nullable|in:ar,en',
////            'token_ip' => 'required',
//            'device_id' => 'required',
//            'device_type' => 'required|in:android,ios',
//            'device_token' => 'required',
//            'device_mac' => 'required',
//            'username' => 'required',
//            'password' => 'required|min:6',
//        ]);
//        if ($validator->fails()) {
//            return responseJson(0, $validator->errors()->first(), $validator->errors());
//        }
//        $Useremail = User::where(['email' => $request->username])->orWhere(['mobile' => $request->username])->first();
//
//        if ($Useremail) {
//            if (Hash::check($request->password, $Useremail->password)) {
//                $request['email'] = $Useremail->email;
//
//                $Useremail->update([
//                    'status' => 'online',
//                    'device_id' => $request->device_id,
//                    'device_type' => $request->device_type,
//                    'device_token' => $request->device_token,
//                    'token_ip' => $request->device_mac,
//                    ]);
//                $params = [
//                    'grant_type' => 'password',
//                    'client_id' => $this->client->id,
//                    'client_secret' => $this->client->secret,
//                    'username' => request('email'),
//                    'password' => request('password'),
//                    'scope' => '*',
//                ];
//                $request->request->add($params);
//
//                $proxy = Request::create('oauth/token', 'POST');
//
//                return Route::dispatch($proxy);
//
//            } else {
//                return responseJson(0, 'بيانات الدخول غير صحيحه');
//            }
//        } else {
//            return responseJson(0, 'بيانات الدخول غير صحيحه');
//        }
//    }


    public
    function refresh(Request $request)
    {
        $this->validate($request, [
            'refresh_token' => 'required',
        ]);

        $params = [
            'grant_type' => 'refresh_token',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'username' => request('email'),
            'password' => request('password')
        ];

        $request->request->add($params);

        $proxy = Request::create('oauth/token', 'POST');

        return Route::dispatch($proxy);
    }

    public
    function checkEmailAndMobile(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'email' => 'required|email|max:255|unique:email_users,email',
            'mobile' => 'required|unique:mobile_users,mobile',
        ]);

        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
        }
        return responseJson(200, true);
    }

    public
    function signup(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'social_unique_id' => ['required_if:login_by,facebook,google', 'unique:users'],
            'device_type' => 'required|in:android,ios',
            'device_token' => 'required',
            'device_id' => 'required',
            'login_by' => 'required|in:manual,facebook,google',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'mobile' => 'required|unique:users,mobile',
            'password' => 'required|min:6',
            'device_mac' => 'required',
            'lang' => 'nullable|in:ar,en',
        ]);

        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }
        try {

            $User = $request->all();

            $User['payment_mode'] = 'CASH';
            $User['status'] = 'online';
            $User['device_mac'] = $request->device_mac;
            $User['password'] = bcrypt($request->password);
            //$User['register_from'] = 'andriod';
            $User = User::create($User);

            //return $User;

            //$User->register_from="andriod";
            $User->register_from='andriod';
            $User->save();

            $emailUser = EmailUser::create([
                'user_id' => $User->id,
                'email' => $request->email,
            ]);

            $mobileUser = MobileUser::create([
                'user_id' => $User->id,
                'mobile' => $request->mobile,
            ]);
            $params = [
                'grant_type' => 'password',
                'client_id' => $this->client->id,
                'client_secret' => $this->client->secret,
                'username' => request('email'),
                'password' => request('password'),
                'scope' => '*',
            ];
            $request->request->add($params);

            $proxy = Request::create('oauth/token', 'POST');

            return Route::dispatch($proxy);

        } catch (Exception $e) {
            if ($request->lang == "ar") {
                return response()->json(['message' => trans("api.Something Went Wrong Ar")], 400);
            } elseif ($request->lang == "en") {
                return response()->json(['message' => trans("api.Something Went Wrong En")], 400);
            } else {
                return response()->json(['message' => trans("api.Something Went Wrong Ar")], 400);
            }
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return JsonResponse
     */
    public
    function checkAccount(Request $request)
    {
        try {
            if ($request->mobile) {
                $Provider = User::where(['mobile' => urlencode($request->mobile)])->first();
                if ($Provider != null) {
                    return response()->json(true);
                } else {
                    return response()->json(false);
                }
            }
            if ($request->social_unique_id) {
                $Provider = User::where(['social_unique_id' => $request->social_unique_id])->get();
                if (count($Provider) > 0) {
                    return response()->json(true);
                } else {
                    return response()->json(false);
                }
            }
            return response()->json(['error' => 'There Is No Unique Id'], 404);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Account Error'], 404);
        }
    }

    public
    function logout(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'mobile' => 'required',
        ]);

        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }

        $user= User::where("mobile",$request->mobile)->first();
        // if($user){

        //     return true;
        // }else{
        //     return "Aloo";
        // }
        if ($user) {
            $allRequests=UserRequests::where('user_id',$user->id)
            ->whereNotIn('status' , ['CANCELLED', 'COMPLETED', 'SCHEDULED'])
            ->get()->first();

            //return $allRequests;
            if($allRequests){
                //return "yes";
                return responseJson(667, trans('.. رجل له ماضي') );
            }else{
                //return "no";
                $user->latitude = null;
                $user->longitude = null;
                $user->status = 'offline';
                $user->device_mac="dummy";
                $user->save();
            }

            // $user->latitude = null;
            // $user->longitude = null;
            // $user->status = 'offline';
            // $user->device_mac="dummy";
            // $user->save();
        }
        else{
            return responseJson(500, trans('api.something_went_wrong') );
        }
        try {
            User::where('id', $request->id)->update(['device_id' => '', 'device_token' => '']);
            //return response()->json(['message' => trans('api.logout_success')],200);
            return responseJson(200, trans('api.logout_success') );
        } catch (Exception $e) {
            //return response()->json(['error' => trans('api.something_went_wrong')], 500);
            return responseJson(500, trans('api.something_went_wrong') );
        }
    }


    /**
     * Show the application dashboard.
     *
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */

    public
    function change_password(Request $request)
    {

        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
            'old_password' => 'required',
            'device_mac' => 'required',
            'lang' => 'nullable|in:ar,en',
        ]);
        if (auth()->user()->device_mac == $request->device_mac) {
            $User = Auth::user();

            if (Hash::check($request->old_password, $User->password)) {
                $User->password = bcrypt($request->password);
                $User->save();

                if ($request->ajax()) {
                    if ($request->lang == "ar") {
                        return response()->json(['message' => trans('api.Password changed successfully! Ar')]);
                    } elseif ($request->lang == "en") {
                        return response()->json(['message' => trans('api.Password changed successfully! En')]);
                    } else {
                        return response()->json(['message' => trans('api.Password changed successfully! Ar')]);
                    }
                } else {
                    return back()->with('flash_success', 'Password Updated');
                }
            } else {
                if ($request->lang == "ar") {
                    return response()->json(['error' => trans('api.Please enter correct password Ar')], 500);
                } elseif ($request->lang == "en") {
                    return response()->json(['error' => trans('api.Please enter correct password En')], 500);
                } else {
                    return response()->json(['error' => trans('api.Please enter correct password Ar')], 500);
                }
            }
        } else {
            return response()->json(['error' => 'api.no'], 401);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return JsonResponse
     */

    public
    function update_location(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'device_mac' => 'required',
            'lang' => 'nullable|in:ar,en',
        ]);

        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }
        if (auth()->user()->device_mac == $request->device_mac) {
            try {
                if ($user = User::find(Auth::user()->id)) {
                    $user->latitude = $request->latitude;
                    $user->longitude = $request->longitude;
                    $user->save();
                }
            } catch (Exception $e) {
                if ($request->lang == "ar") {
                    return response()->json(['message' => trans("api.Something Went Wrong Ar")], 400);
                } elseif ($request->lang == "en") {
                    return response()->json(['message' => trans("api.Something Went Wrong En")], 400);
                } else {
                    return response()->json(['message' => trans("api.Something Went Wrong Ar")], 400);
                }
            }
        } else {
            return response()->json(['error' => 'api.no'], 401);
        }
    }


    /**
     * Show the application dashboard.
     *
     * @return User|User[]|Collection|\Illuminate\Database\Eloquent\Model|JsonResponse
     */

    public
    function details(Request $request)
    {
        //return "Alo";
        //return $request->device_mac;

        $this->validate($request,[
            'device_type' => 'in:android,ios',
            'device_mac' => 'required',
            'lang' => 'nullable|in:ar,en',
        ]);
        //return $request->device_mac;
        //return $request;
        if (auth()->user()->device_mac == $request->device_mac) {
            try {


                if ($user = User::find(Auth::user()->id)) {
                    // $trip = UserRequests::where('user_id', $request->user()->id)->where('status','=', [ 'ACCEPTED','ARRIVED','DROPPED'])->get()->first();
                    if ($request->has('device_token')) {
                        $user->device_token = $request->device_token;
                    }

                    if ($request->has('device_type')) {
                        $user->device_type = $request->device_type;
                    }

                    if ($request->has('device_id')) {
                        $user->device_id = $request->device_id;
                    }

                    $user->save();

                    $user->currency = Setting::get('currency');
                    $user->sos = Setting::get('sos_number', '911');
                //    if ( $trip != null) {

                    //    return response()->json(['user' => $user, 'trip' => $trip]);
                //    } else {
                    return $user;
                //    }

                } else {
                    if ($request->lang == "ar") {
                        return response()->json(['message' => trans("api.user_not_found Ar")], 422);
                    } elseif ($request->lang == "en") {
                        return response()->json(['message' => trans("api.user_not_found En")], 422);
                    } else {
                        return response()->json(['message' => trans("api.user_not_found Ar")], 422);
                    }
                }
            } catch (Exception $e) {
                //return $e;
                if ($request->lang == "ar") {
                    return response()->json(['message' => trans("api.Something Went Wrong Ar")], 422);
                } elseif ($request->lang == "en") {
                    return response()->json(['message' => trans("api.Something Went Wrong En")], 422);
                } else {
                    return response()->json(['message' => trans("api.Something Went Wrong Ar")], 422);
                }
                return $e;
            }
        } else {
            return response()->json(['error' => trans('api.no')], 401);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */

    public
    function update_profile(Request $request)
    {

        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'max:255',
            'email' => 'email|unique:users,email,' . Auth::user()->id,
            'mobile' => 'required',
            'picture' => 'mimes:jpeg,bmp,png',
            'device_mac' => 'required',
        ]);
        if (auth()->user()->device_mac == $request->device_mac) {
            try {

                $user = User::findOrFail(Auth::user()->id);

                if ($request->has('first_name')) {
                    $user->first_name = $request->first_name;
                }

                if ($request->has('last_name')) {
                    $user->last_name = $request->last_name;
                }

                if ($request->has('email')) {
                    $user->email = $request->email;
                }

               /*  if ($request->has('mobile')) {
                    $user->mobile = $request->mobile;
                }*/
                    /*
                if ($request->picture != "") {
                    Storage::delete($user->picture);
                    $user->picture = $request->picture->store('user/profile');
                }*/
                if ($request->hasFile('picture')) {
                    $picture = $request->picture;
                    //. $picture->getClientOriginalName();
                    $code = rand(111111111, 999999999);
                    $avatar_new_name = time().$code."pp" ;
                    $picture->move('uploads/user/', $avatar_new_name);

                    $user->picture = 'uploads/user/' . $avatar_new_name;
                   // $Provider->save();
                }
                $user->save();
                return responseJson(200, trans('api.successfully'), $user);
//                if ($request->ajax()) {
//                    return response()->json(['message' => trans('api.successfully')], $user);
//                } else {
//                    return back()->with('flash_success', trans('api.user.profile_updated'));
//                }
            } catch (ModelNotFoundException $e) {
//                return response()->json(['error' => trans('api.user.user_not_found')], 500);
                if ($request->lang == "ar") {
                    return response()->json(['error' => trans('api.user_not_found Ar')], 500);
                } elseif ($request->lang == "en") {
                    return response()->json(['error' => trans('api.user_not_found En')], 500);
                } else {
                    return response()->json(['error' => trans('api.user_not_found Ar')], 500);
                }
            }
        } else {
            return response()->json(['error' => 'api.no'], 401);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return ServiceType[]|Collection|JsonResponse
     */

    public
    function services(Request $request)
    {
        $this->validate($request, [
            'device_mac' => 'required',
            'lang' => 'nullable|in:ar,en',
        ]);
        if (auth()->user()->device_mac == $request->device_mac) {
            if ($serviceList = ServiceType::where('status', '=', 1)->get()) {
                return $serviceList;
            } else {
//            return response()->json(['error' => trans('api.services_not_found')], 500);
                if ($request->lang == "ar") {
                    return response()->json(['message' => trans('api.services_not_found Ar')], 500);
                } elseif ($request->lang == "en") {
                    return response()->json(['message' => trans('api.services_not_found En')], 500);
                } else {
                    return response()->json(['message' => trans('api.services_not_found Ar')], 500);
                }
            }
        } else {
            return response()->json(['error' => 'api.no'], 401);
        }
    }


    /**
     * Show the application dashboard.
     *
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws Exception
     */

    public
    function send_request(Request $request)
    {

        $validator = validator()->make($request->all(), [
            's_latitude' => 'required|numeric',
            'd_latitude' => 'required|numeric',
            's_longitude' => 'required|numeric',
            'd_longitude' => 'required|numeric',
            'service_type' => 'required|numeric|exists:service_types,id',
            'promo_code' => 'exists:promocodes,promo_code',
            'distance' => 'required|numeric',
            'use_wallet' => 'numeric',
            'payment_mode' => 'required|in:CASH,CARD,PAYPAL',
            'card_id' => ['required_if:payment_mode,CARD', 'exists:cards,card_id,user_id,' . Auth::user()->id],
        ]);

        Log::info('New Request from User: ' . Auth::user()->id);
        Log::info('Request Details:', $request->all());

        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }

        $ActiveRequests = UserRequests::PendingRequest(Auth::user()->id)->count();

        if ($ActiveRequests > 0) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.ride.request_inprogress')], 500);
            } else {
                return redirect('dashboard')->with('flash_error', 'Already request is in progress. Try again later');
            }
        }

        if ($request->has('schedule_date') && $request->has('schedule_time')) {
            $beforeschedule_time = (new Carbon("$request->schedule_date $request->schedule_time"))->subHour(1);
            $afterschedule_time = (new Carbon("$request->schedule_date $request->schedule_time"))->addHour(1);

            $CheckScheduling = UserRequests::where('status', 'SCHEDULED')
                ->where('user_id', Auth::user()->id)
                ->whereBetween('schedule_at', [$beforeschedule_time, $afterschedule_time])
                ->count();


            if ($CheckScheduling > 0) {
                if ($request->ajax()) {
                    return response()->json(['error' => trans('api.ride.request_scheduled')], 500);
                } else {
                    return redirect('dashboard')->with('flash_error', 'Already request is Scheduled on this time.');
                }
            }

        }

        $distance = Setting::get('provider_search_radius', '10');
        $latitude = $request->s_latitude;
        $longitude = $request->s_longitude;
        $service_type = $request->service_type;

        $Providers = Provider::with('service')
            ->select(DB::Raw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) AS distance"), 'id')
            ->where('status', 'approved')
            ->whereRaw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
            ->whereHas('service', function ($query) use ($service_type) {
                $query->where('status', 'active');
                $query->where('service_type_id', $service_type);
            })
            ->orderBy('distance')
            ->get();

        // List Providers who are currently busy and add them to the filter list.

        if (count($Providers) == 0) {
            if ($request->ajax()) {
                // Push Notification to User
                return response()->json(['message' => trans('api.ride.no_providers_found')]);
            } else {
                return back()->with('flash_success', 'No Providers Found! Please try again.');
            }
        }

        try {

            $details = "https://maps.googleapis.com/maps/api/directions/json?origin=" . $request->s_latitude . "," . $request->s_longitude . "&destination=" . $request->d_latitude . "," . $request->d_longitude . "&mode=driving&key=AIzaSyBSxfcR__-NJzR8i7HRM50WKAxIVBzrtYo";

            $json = curl($details);

            $details = json_decode($json, TRUE);

            $route_key = $details['routes'][0]['overview_polyline']['points'];

            $UserRequest = new UserRequests;
            $UserRequest->booking_id = Helper::generate_booking_id();
            $UserRequest->user_id = Auth::user()->id;
            $UserRequest->current_provider_id = $Providers[0]->id;
            $UserRequest->service_type_id = $request->service_type;
            $UserRequest->payment_mode = $request->payment_mode;

            // I'M
            $UserRequest->total_trips = $UserRequest->total_trips + 1;

            $UserRequest->status = 'SCHEDULED';

            $UserRequest->s_address = $request->s_address ?: "";
            $UserRequest->d_address = $request->d_address ?: "";

            $UserRequest->s_latitude = $request->s_latitude;
            $UserRequest->s_longitude = $request->s_longitude;

            $UserRequest->d_latitude = $request->d_latitude;
            $UserRequest->d_longitude = $request->d_longitude;
            $UserRequest->distance = $request->distance;

            if (Auth::user()->wallet_balance > 0) {
                $UserRequest->use_wallet = $request->use_wallet ?: 0;
            }

            $UserRequest->assigned_at = Carbon::now();
            $UserRequest->route_key = $route_key;

            if ($Providers->count() <= Setting::get('surge_trigger') && $Providers->count() > 0) {
                $UserRequest->surge = 1;
            }

            if ($request->has('schedule_date') && $request->has('schedule_time')) {
                $UserRequest->schedule_at = date("Y-m-d H:i:s", strtotime("$request->schedule_date $request->schedule_time"));
            }

            $UserRequest->save();

            Log::info('New Request id : ' . $UserRequest->id . ' Assigned to provider : ' . $UserRequest->current_provider_id);


            // update payment mode

            User::where('id', Auth::user()->id)->update(['payment_mode' => $request->payment_mode]);

            if ($request->has('card_id')) {

                Card::where('user_id', Auth::user()->id)->update(['is_default' => 0]);
                Card::where('card_id', $request->card_id)->update(['is_default' => 1]);
            }

            (new SendPushNotification)->IncomingRequest($Providers[0]->id);

            foreach ($Providers as $key => $Provider) {

                $Filter = new RequestFilter;
                // Send push notifications to the first provider
                // incoming request push to provider
                $Filter->request_id = $UserRequest->id;
                $Filter->provider_id = $Provider->id;
                $Filter->save();
            }

            if ($request->ajax()) {
                return response()->json([
                    'message' => 'New request Created!',
                    'request_id' => $UserRequest->id,
                    'current_provider' => $UserRequest->current_provider_id,
                ]);
            } else {
                return redirect('dashboard');
            }

        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
            } else {
                return back()->with('flash_error', 'Something went wrong while sending request. Please try again.');
            }
        }
    }

    public
    function editTrip(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'trip_id' => 'required|exists:user_requests,id',
            'schedule_date' => 'required',
            'schedule_time' => 'required',
            'device_mac' => 'required',
        ]);
        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }
        if (auth()->user()->device_mac == $request->device_mac) {
            $trip = UserRequests::find($request->trip_id);
            if ($trip) {
                if ($trip->status == 'SCHEDULED') {
//                dd($trip->schedule_at);
                    $current = Carbon::now();
//            $trialExpires = $current->addHour();
                    $diffHours = $current->diffInHours($trip->schedule_at);
//            dd($diffHours >= 1);
                    if ($diffHours >= 1) {
                        if ($request->has('schedule_date') && $request->has('schedule_time')) {
                            $trip->schedule_at = date("Y-m-d H:i:s", strtotime("$request->schedule_date $request->schedule_time"));
                        }
                        $trip->update([
                            'schedule_at' => $trip->schedule_at,
                            'status' => 'SCHEDULED',
                        ]);
                        return responseJson(1, trans('admin.editMessageSuccess'), $trip);
                    } else {
//                return response()->json(false);
//                    return responseJson(0, trans('admin.Unable to edit on trip'));
                        return response()->json(['error' => trans('admin.Selected time is not allowed')], 422);
                    }
                } else {
                    return response()->json(['error' => trans('admin.Trip Not SCHEDULED')], 422);
                }
            } else {
                return response()->json(['error' => trans('admin.Trip Not Found')], 422);
            }
        } else {
            return response()->json(['error' => trans('api.no')], 401);
        }
    }

    public
    function deleteTrip(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'trip_id' => 'required|exists:user_requests,id',
            'cancel_reason' => 'nullable',
            'device_mac' => 'required',
        ]);
        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }
        if (auth()->user()->device_mac == $request->device_mac) {
            $trip = UserRequests::find($request->trip_id);
            if ($trip) {
                if ($trip->user_id == auth()->user()->id) {
                    if ($trip->status == 'SCHEDULED') {

                        $current = Carbon::now();
                        $diffHours = $current->diffInHours($trip->schedule_at);
                        if ($diffHours >= 1) {
                            $trip->update([
                                'status' => 'CANCELLED',
                                'cancelled_by' => 'USER',
                                'cancel_reason' => $request->cancel_reason,
                                'schedule_at' => $trip->schedule_at,
                            ]);
                            return responseJson(200, trans('admin.deleteMessageSuccess'), $trip);
                        } else {
                            return response()->json(['error' => trans('admin.Selected time is not allowed')], 422);
                        }
                    } else {
                        return response()->json(['message' => trans('admin.Trip Not SCHEDULED')], 422);
                    }
                } else {
                    return response()->json(['message' => trans('admin.User Not Found')], 422);
                }
            } else {
//            return responseJson(422, trans('admin.Trip Not Found'), $trip);
                return response()->json(['message' => trans('admin.Trip Not Found')], 422);
            }
        } else {
            return response()->json(['error' => trans('api.no')], 401);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return JsonResponse
     */


    public
    function App_Details(Request $request)
    {

        $App_Status = Setting::get('app_status', '1');
        $App_Name = Setting::get('site_title', 'Al Baz');
        $App_Icon = Setting::get('site_icon', 'http://www.ailbaz.com/ailbaz_server/public/img/logo-black.png');
        $App_Splash = Setting::get('splash', 'http://www.ailbaz.com/ailbaz_server/public/img/logo-black.png');
        $App_Logo = Setting::get('site_logo', 'http://www.ailbaz.com/ailbaz_server/public/img/logo-black.png');
        $App_Msg = Setting::get('app_msg', 'System is under Maintenance');
        $Phone_Number = Setting::get('contact_number', '');
        $Sos = Setting::get('sos_number', '');
        $Currency = Setting::get('currency', '');
        $Email = Setting::get('contact_email', '');
        $Interval_Time = Setting::get('interval_time', '3000');

        // $user=User::find($request->device_mac);
        // $device_mac=$user->device_mac;

        return response()->json([
            'App_Name' => $App_Name,
            'App_Icon' => $App_Icon,
            'App_Logo' => $App_Logo,
            'App_Splash' => $App_Splash,
            'App_Status' => $App_Status,
            'Sos' => $Sos,
            'Currency' => $Currency,
            'App_Msg' => $App_Msg,
            'Phone_Number' => $Phone_Number,
            'Email' => $Email,
            'Interval_Time' => $Interval_Time
        ]);
    }


    /**
     * Show the application dashboard.
     *
     * @return JsonResponse
     */

    public
    function Destroy_App()
    {
        if ($user = User::find(Auth::user()->id)) {
            $user->latitude = null;
            $user->longitude = null;
            $user->save();
            return response()->json(['message' => trans('api.user.app_destroyed')]);
        } else {
            return response()->json(['error' => trans('api.user.user_not_found')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */


    public
    function cancel_request(Request $request)
    {

        $this->validate($request, [
            'request_id' => 'required|numeric|exists:user_requests,id,user_id,' . Auth::user()->id,
        ]);

        try {

            $UserRequest = UserRequests::findOrFail($request->request_id);

            if ($UserRequest->status == 'CANCELLED') {
                if ($request->ajax()) {
                    return response()->json(['error' => trans('api.ride.already_cancelled')], 500);
                } else {
                    return back()->with('flash_error', 'Request is Already Cancelled!');
                }
            }

            if (in_array($UserRequest->status, ['SEARCHING', 'STARTED', 'ARRIVED', 'SCHEDULED'])) {

                if ($UserRequest->status != 'SEARCHING') {
                    $this->validate($request, [
                        'cancel_reason' => 'max:255',
                    ]);
                }

                $UserRequest->status = 'CANCELLED';
                $UserRequest->cancel_reason = $request->cancel_reason;
                $UserRequest->cancelled_by = 'USER';
                $UserRequest->save();

                RequestFilter::where('request_id', $UserRequest->id)->delete();

                if ($UserRequest->status != 'SCHEDULED') {

                    if ($UserRequest->provider_id != 0) {

                        ProviderService::where('provider_id', $UserRequest->provider_id)->update(['status' => 'active']);

                    }
                }

                // Send Push Notification to User
                (new SendPushNotification)->UserCancellRide($UserRequest);

                if ($request->ajax()) {
                    return response()->json(['message' => trans('api.ride.ride_cancelled')]);
                } else {
                    return redirect('dashboard')->with('flash_success', 'Request Cancelled Successfully');
                }

            } else {
                if ($request->ajax()) {
                    return response()->json(['error' => trans('api.ride.already_onride')], 500);
                } else {
                    return back()->with('flash_error', 'Service Already Started!');
                }
            }
        } catch (ModelNotFoundException $e) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            } else {
                return back()->with('flash_error', 'No Request Found!');
            }
        }

    }

    /**
     * Show the request status check.
     *
     * @return JsonResponse
     */

    public
    function request_status_check()
    {

        try {
            $check_status = ['CANCELLED', 'SCHEDULED'];
            $UserRequests = UserRequests::UserRequestStatusCheck(Auth::user()->id, $check_status)
                ->get()
                ->toArray();
            $search_status = ['SEARCHING', 'SCHEDULED'];
            $UserRequestsFilter = UserRequests::UserRequestAssignProvider(Auth::user()->id, $search_status)->get();

            // Log::info($UserRequestsFilter);

            $Timeout = Setting::get('provider_select_timeout', 180);

            if (!empty($UserRequestsFilter)) {
                for ($i = 0; $i < sizeof($UserRequestsFilter); $i++) {
                    $ExpiredTime = $Timeout - (time() - strtotime($UserRequestsFilter[$i]->assigned_at));
                    if ($UserRequestsFilter[$i]->status == 'SEARCHING' && $ExpiredTime < 0) {
                        $Providertrip = new TripController();
                        $Providertrip->assign_next_provider($UserRequestsFilter[$i]->id);
                    } else if ($UserRequestsFilter[$i]->status == 'SEARCHING' && $ExpiredTime > 0) {
                        break;
                    }
                }
            }

            return response()->json(['data' => $UserRequests]);

        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */


    public
    function rate_provider(Request $request)
    {

        $this->validate($request, [
            'request_id' => 'required|integer|exists:user_requests,id,user_id,' . Auth::user()->id,
            'rating' => 'required|integer|in:1,2,3,4,5',
            'comment' => 'max:255',
        ]);

        $UserRequests = UserRequests::where('id', $request->request_id)
            ->where('status', 'COMPLETED')
            ->where('paid', 0)
            ->first();

        if ($UserRequests) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.user.not_paid')], 500);
            } else {
                return back()->with('flash_error', 'Service Already Started!');
            }
        }

        try {

            $UserRequest = UserRequests::findOrFail($request->request_id);

            if ($UserRequest->rating == null) {
                UserRequestRating::create([
                    'provider_id' => $UserRequest->provider_id,
                    'user_id' => $UserRequest->user_id,
                    'request_id' => $UserRequest->id,
                    'user_rating' => $request->rating,
                    'user_comment' => $request->comment,
                ]);
            } else {
                $UserRequest->rating->update([
                    'user_rating' => $request->rating,
                    'user_comment' => $request->comment,
                ]);
            }

            $UserRequest->user_rated = 1;
            $UserRequest->save();

            $average = UserRequestRating::where('provider_id', $UserRequest->provider_id)->avg('user_rating');

            Provider::where('id', $UserRequest->provider_id)->update(['rating' => $average]);

            // Send Push Notification to Provider
            if ($request->ajax()) {
                return response()->json(['message' => trans('api.ride.provider_rated')]);
            } else {
                return redirect('dashboard')->with('flash_success', 'Driver Rated Successfully!');
            }
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
            } else {
                return back()->with('flash_error', 'Something went wrong');
            }
        }

    }


    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return JsonResponse
     */

    public function trips(Request $request)
    {
        $this->validate($request, [
            'device_mac' => 'required',
            'lang' => 'nullable|in:ar,en',
        ]);
        if (User::find(Auth::user()->id)) {
            if (auth()->user()->device_mac == $request->device_mac) {
                try {
                    $UserRequests = UserRequests::UserTrips(Auth::user()->id)->take(10)->get();
                    if (!empty($UserRequests)) {
                        $map_icon = asset('asset/img/marker-start.png');
                        foreach ($UserRequests as $key => $value) {
                            $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?" .
                                "autoscale=1" .
                                "&size=320x130" .
                                "&maptype=terrian" .
                                "&format=png" .
                                "&visual_refresh=true" .
                                "&markers=icon:" . $map_icon . "%7C" . $value->s_latitude . "," . $value->s_longitude .
                                "&markers=icon:" . $map_icon . "%7C" . $value->d_latitude . "," . $value->d_longitude .
                                "&path=color:0x191919|weight:3|enc:" . $value->route_key .
                                "&key=AIzaSyBSxfcR__-NJzR8i7HRM50WKAxIVBzrtYo";
                        }
                    }
                    return $UserRequests;
                } catch (Exception $e) {
//                    return response()->json(['error' => trans('api.something_went_wrong')]);
                    if ($request->lang == "ar") {
                        return response()->json(['message' => trans("api.Something Went Wrong Ar")], 422);
                    } elseif ($request->lang == "en") {
                        return response()->json(['message' => trans("api.Something Went Wrong En")], 422);
                    } else {
                        return response()->json(['message' => trans("api.Something Went Wrong Ar")], 422);
                    }
                }
            } else {
                return response()->json(['error' => trans('api.no')], 401);
            }
        } else {
            if ($request->lang == "ar") {
                return response()->json(['message' => trans('api.user_not_found Ar')], 422);
            } elseif ($request->lang == "en") {
                return response()->json(['message' => trans('api.user_not_found En')], 422);
            } else {
                return response()->json(['message' => trans('api.user_not_found Ar')], 422);
            }
        }
    }


    function GetDrivingDistance(Request $request)
    {
        $validator = validator()->make($request->all(), [
            's_latitude' => 'required|numeric',
            's_longitude' => 'required|numeric',
            'd_latitude' => 'required|numeric',
            'd_longitude' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $request->s_latitude . "," . $request->s_longitude . "&destinations=" . $request->d_latitude . "," . $request->d_longitude . "&mode=driving&sensor=false&key=AIzaSyBSxfcR__-NJzR8i7HRM50WKAxIVBzrtYo";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($result, true);
//        dd($result);
        $distance = $response['rows'][0]['elements'][0]['distance']['text'];
        $time = $response['rows'][0]['elements'][0]['duration']['text'];
        return responseJson(200, trans('admin.createMessageSuccess'), [
            'distance' => $distance,
            'time' => $time
        ]);
//        return array('distance' => $distance, 'time' => $time);
    }

    /**
     * Show the application dashboard.
     *
     * @return JsonResponse
     */

    public
    function estimated_fare(Request $request)
    {

        $someJSON = '[{"name":"Jonathan Suh","gender":"male"},{"name":"William Philbin","gender":"male"},{"name":"Allison McKinnery","gender":"female"}]';
        $someArray = json_decode($someJSON, true);
        //return response()->$someArray;
        //return response()->json($someArray);
        //echo response()->json(["name"=>"ali"]);

        //Log::info('Estimate', $request->all());
        $validator = validator()->make($request->all(), [
            's_latitude' => 'required|numeric',
            's_longitude' => 'required|numeric',
            'd_latitude' => 'required|numeric',
            'd_longitude' => 'required|numeric',
            'service_type' => 'required|numeric|exists:service_types,id',
            'device_mac' => 'required',
            'lang' => 'nullable|in:ar,en',
        ]);
        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }
        if (auth()->user()->device_mac == $request->device_mac) {

            try {

                $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins="
                    . $request->s_latitude . "," . $request->s_longitude . "&destinations="
                    . $request->d_latitude . "," . $request->d_longitude .
                    "&mode=driving&sensor=false&key=AIzaSyBSxfcR__-NJzR8i7HRM50WKAxIVBzrtYo";
                    $json = file_get_contents($details);

                   // $json = curl($details);
                   $details = json_decode($json, TRUE);

                  //  return response()->json($details);

                    $meter = $details['rows'][0]['elements'][0]['distance']['value'];
                   // return response()->json(['message' => trans("000")], 422);
//                dd(env('GOOGLE_MAP_KEY'));

                    $time = $details['rows'][0]['elements'][0]['duration']['text'];
                    $seconds = $details['rows'][0]['elements'][0]['duration']['value'];
                    $kilometer = round($meter / 1000);
                    $minutes = round($seconds / 60);

                    $tax_percentage = Setting::get('tax_percentage');
                    $commission_percentage = Setting::get('commission_percentage');
                    $service_type = ServiceType::findOrFail($request->service_type);

                    $price = $service_type->fixed;


                    if ($service_type->calculator == 'MIN') {
                        $price += $service_type->minute * $minutes;
                    } else if ($service_type->calculator == 'HOUR') {
                        $price += $service_type->minute * 60;
                    } else if ($service_type->calculator == 'DISTANCE') {
                        $price += ($kilometer * $service_type->price);
                    } else if ($service_type->calculator == 'DISTANCEMIN') {
                        $price += ($kilometer * $service_type->price) + ($service_type->minute * $minutes);
                    } else if ($service_type->calculator == 'DISTANCEHOUR') {
                        $price += ($kilometer * $service_type->price) + ($service_type->minute * $minutes * 60);
                    } else {
                        $price += ($kilometer * $service_type->price);
                    }

                    $tax_price = ($tax_percentage / 100) * $price;
                    $total = $price + $tax_price;

                    $ActiveProviders = ProviderService::where('service_type_id', $request->service_type)->where('status', 'active')->get()->pluck('provider_id');
                    $distance = Setting::get('provider_search_radius', '10');
                    $latitude = $request->s_latitude;
                    $longitude = $request->s_longitude;
//                dd($distance);

                    $Providers = Provider::whereIn('id', $ActiveProviders)
                        ->where('status', 'approved')
                        ->whereRaw("(1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                        ->get();

                    $surge = 0;

//                if ($Providers->count() <= Setting::get('surge_trigger') && $Providers->count() > 0) {
//                    $surge_price = (Setting::get('surge_percentage') / 100) * $total;
//                    $total += $surge_price;
//                    $surge = 1;
//                }

                    return response()->json([
                        'estimated_fare' => round($total, 2),
                        'distance' => $meter,
                        'time' => $time,
                        'surge' => $surge,
//                    'surge' => $surge,
//                    'surge' => $surge,
                        'surge_value' => '1.4X',
                        'tax_price' => $tax_price,
                        'base_price' => $service_type->fixed,
                        //'wallet_balance' => Auth::user()->wallet_balance
                    ]);

            } catch (Exception $e) {
                return $e;
            }
        } else {
            return response()->json(['error' => trans('api.no')], 401);
       }

    }

    /**
     * Show the application dashboard.
     *
     * @return JsonResponse|\Illuminate\Http\Response
     */

    public function trip_details(Request $request)
    {

//         dd("ememememeemememe");
// die();
        $this->validate($request, [
            'request_id' => 'required|integer|exists:user_requests,id',
            'lang' => 'nullable|in:ar,en',
            'device_mac' => 'required',
        ]);
        if (auth()->user()->device_mac == $request->device_mac) {
            try {
                $UserRequests = UserRequests::UserTripDetails(Auth::user()->id, $request->request_id)->get();
                if (!empty($UserRequests)) {
                    $map_icon = asset('asset/img/marker-start.png');
                    foreach ($UserRequests as $key => $value) {
                        $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?" .
                            "autoscale=1" .
                            "&size=320x130" .
                            "&maptype=terrian" .
                            "&format=png" .
                            "&visual_refresh=true" .
                            "&markers=icon:" . $map_icon . "%7C" . $value->s_latitude . "," . $value->s_longitude .
                            "&markers=icon:" . $map_icon . "%7C" . $value->d_latitude . "," . $value->d_longitude .
                            "&path=color:0x000000|weight:3|enc:" . $value->route_key .
                            "&key=AIzaSyBSxfcR__-NJzR8i7HRM50WKAxIVBzrtYo";
                    }
                }
                return $UserRequests;
            } catch (Exception $e) {
//            return response()->json(['error' => trans('api.something_went_wrong')]);
                if ($request->lang == "ar") {
                    return response()->json(['message' => trans("api.Something Went Wrong Ar")], 400);
                } elseif ($request->lang == "en") {
                    return response()->json(['message' => trans("api.Something Went Wrong En")], 400);
                } else {
                    return response()->json(['message' => trans("api.Something Went Wrong Ar")], 400);
                }
            }
        } else {
            return response()->json(['error' => 'api.no'], 401);
        }

    }

    /**
     * get all promo code.
     *
     * @param Request $request
     * @return JsonResponse
     */

    public
    function promocodes(Request $request)
    {
        $this->validate($request, [
            'device_mac' => 'required',
        ]);

        if (auth()->user()->device_mac == $request->device_mac) {

            try {
                $this->check_expiry();
                $emad= PromocodeUsage::Active()
                    ->where('user_id', Auth::user()->id)
                    ->with('promocode')
                    ->get();

                return responseJson(200,"Done",$emad);
                
            } catch (Exception $e) {
                return response()->json(['message' => trans("api.Something Went Wrong")], 400);
            }
        } else {
            return response()->json(['error' => trans('api.no')], 401);
        }
    }



    public
    function check_expiry()
    {
        try {
            $Promocode = Promocode::all();
            foreach ($Promocode as $index => $promo) {
                if (date("Y-m-d") > $promo->expiration) {
                    $promo->status = 'EXPIRED';
                    $promo->save();
                    $promo->users()->where('promocode_id', $promo->id)->update(['status' => 'EXPIRED']);
                } else {
                    $promo->users()->where('promocode_id', $promo->id)->update(['status' => 'ADDED']);
                }
            }
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }


    /**
     * add promo code.
     *
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */

    public
    function add_promocode(Request $request)
    {

        $this->validate($request, [
            'promocode' => 'required|exists:promocodes,promo_code',
        ]);

        try {

            $find_promo = Promocode::where('promo_code', $request->promocode)->first();
                   //            dd((date("Y-m-d") < $find_promo->expiration))
            $promo = PromocodeUsage::where('promocode_id', $find_promo->id)->where('user_id', auth()->user()->id)->first();
                  //            dd($promo)
            if ($promo->status != 'EXPIRED') {
                if ($promo->status != 'USED') {
                    if ($find_promo->status == 'ADDED') {
                        if ($promo->status == 'ADDED' || (date("Y-m-d") < $find_promo->expiration)) {
                            $promo->update([
                                'status' => 'USED'
                            ]);
                            return response()->json([
                                'message' => trans('api.promocode_applied'),
                                'code' => 'promocode_applied'
                            ]);
                        } else {
                            return response()->json([
                                'message' => trans('api.promocode_expired'),
                                'code' => 'promocode_expired'
                            ]);
                        }
                    } else {
                        return response()->json([
                            'message' => trans('api.promocode_expired'),
                            'code' => 'promocode_expired'
                        ]);
                    }
                } else {
                    return response()->json([
                        'message' => trans('api.promocode_already_in_use'),
                        'code' => 'promocode_already_in_use'
                    ]);
                }
            } else {
                return response()->json([
                    'message' => trans('api.promocode_expired'),
                    'code' => 'promocode_expired'
                ]);
            }
//            if ($find_promo->status == 'ADDED' || (date("Y-m-d") > $find_promo->expiration)) {
//
//                if ($request->ajax()) {
//
//                    return response()->json(true);
//
//                } else {
//
//                    return back()->with('flash_error', trans('api.promocode_expired'));
//                }
//
//            } elseif (PromocodeUsage::where('promocode_id', $find_promo->id)->where('user_id', Auth::user()->id)->where('status', 'ADDED')->count() > 0) {
//                if ($request->ajax()) {
//
//                    return response()->json(true);
//
//                } else {
//                    return back()->with('flash_error', 'Promocode Already in use');
//                }
//
//            } else {
//
//                $promo = new PromocodeUsage;
//                $promo->promocode_id = $find_promo->id;
//                $promo->user_id = Auth::user()->id;
//                $promo->status = 'ADDED';
//                $promo->save();
//
//                if ($request->ajax()) {
//
//                    return response()->json([
//                        'message' => trans('api.promocode_applied'),
//                        'code' => 'promocode_applied'
//                    ]);
//
//                } else {
//                    return back()->with('flash_success', trans('api.promocode_applied'));
//                }
//            }

        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
            } else {
                return back()->with('flash_error', 'Something Went Wrong');
            }
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return JsonResponse|\Illuminate\Http\Response
     */

    public
    function upcoming_trips(Request $request)
    {
        $this->validate($request, [
            'device_mac' => 'required',
            'lang' => 'nullable|in:ar,en',
        ]);
        if (auth()->user()->device_mac == $request->device_mac) {
            try {
                $UserRequests = UserRequests::UserUpcomingTrips(Auth::user()->id)->get();
                if (!empty($UserRequests)) {
                    $map_icon = asset('asset/img/marker-start.png');
                    foreach ($UserRequests as $key => $value) {
                        $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?" .
                            "autoscale=1" .
                            "&size=320x130" .
                            "&maptype=terrian" .
                            "&format=png" .
                            "&visual_refresh=true" .
                            "&markers=icon:" . $map_icon . "%7C" . $value->s_latitude . "," . $value->s_longitude .
                            "&markers=icon:" . $map_icon . "%7C" . $value->d_latitude . "," . $value->d_longitude .
                            "&path=color:0x000000|weight:3|enc:" . $value->route_key .
                            "&key=AIzaSyBSxfcR__-NJzR8i7HRM50WKAxIVBzrtYo";
                    }
                }
                return $UserRequests;
            } catch (Exception $e) {
                if ($request->lang == "ar") {
                    return response()->json(['message' => trans("api.Something Went Wrong Ar")], 400);
                } elseif ($request->lang == "en") {
                    return response()->json(['message' => trans("api.Something Went Wrong En")], 400);
                } else {
                    return response()->json(['message' => trans("api.Something Went Wrong Ar")], 400);
                }
            }
        } else {
            return response()->json(['error' => 'api.no'], 401);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return JsonResponse|\Illuminate\Http\Response
     */

    public
    function upcoming_trip_details(Request $request)
    {

        $this->validate($request, [
            'request_id' => 'required|integer|exists:user_requests,id',
            'lang' => 'nullable|in:ar,en',
            'device_mac' => 'required',
        ]);
        if (auth()->user()->device_mac == $request->device_mac) {
            try {
                $UserRequests = UserRequests::UserUpcomingTripDetails(Auth::user()->id, $request->request_id)->get();
                if (!empty($UserRequests)) {
                    $map_icon = asset('asset/img/marker-start.png');
                    foreach ($UserRequests as $key => $value) {
                        $UserRequests[$key]->static_map = "https://maps.googleapis.com/maps/api/staticmap?" .
                            "autoscale=1" .
                            "&size=320x130" .
                            "&maptype=terrian" .
                            "&format=png" .
                            "&visual_refresh=true" .
                            "&markers=icon:" . $map_icon . "%7C" . $value->s_latitude . "," . $value->s_longitude .
                            "&markers=icon:" . $map_icon . "%7C" . $value->d_latitude . "," . $value->d_longitude .
                            "&path=color:0x000000|weight:3|enc:" . $value->route_key .
                            "&key=AIzaSyBSxfcR__-NJzR8i7HRM50WKAxIVBzrtYo";
                    }
                }
                return $UserRequests;
            } catch (Exception $e) {
                if ($request->lang == "ar") {
                    return response()->json(['message' => trans("api.Something Went Wrong Ar")], 400);
                } elseif ($request->lang == "en") {
                    return response()->json(['message' => trans("api.Something Went Wrong En")], 400);
                } else {
                    return response()->json(['message' => trans("api.Something Went Wrong Ar")], 400);
                }
            }
        } else {
            return response()->json(['error' => 'api.no'], 401);
        }
    }


    /**
     * Show the nearby providers.
     *
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */

    public function show_providers(Request $request)
    {

        $this->validate($request, [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'service' => 'numeric|exists:service_types,id'
        ]);
        try {
            $distance = Setting::get('provider_search_radius', '10');
            $latitude = $request->latitude;
            $longitude = $request->longitude;

            if ($request->has('service')) {
                $ActiveProviders = ProviderService::AvailableServiceProvider($request->service)->get()->pluck('provider_id');
                $Providers = Provider::whereIn('id', $ActiveProviders)
                    ->where('status', 'approved')->whereHas('service', function ($service) {
                        $service->where('status', 'offline');
                    })
                    ->whereRaw("(1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                    ->get(['id', 'latitude', 'longitude']);
            } else {
                $Providers = Provider::where('status', 'approved')->whereHas('service', function ($service) {
                    $service->where('status', 'active');
                })
                    ->whereRaw("(1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                    ->get(['id', 'latitude', 'longitude']);
            }
            if (count($Providers) == 0) {
                if ($request->ajax()) {
                    return response()->json(['message' => "No Providers Found"]);
                } else {
                    return response()->json(['message' => "No Providers Found"]);
//                    return back()->with('flash_success', 'No Providers Found! Please try again.');
                }
            }

            $user_one = UserRequests::where('user_id', auth()->user()->id)
                ->where('status', '!=', 'SCHEDULED')
                ->where('status', '!=', 'SEARCHING')
                ->where('status', '!=', 'CANCELLED')
                ->where('status', '!=', 'COMPLETED')->first();
// dd($user_one);
            if ($user_one != null) {
                /*$url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $user_one->s_latitude . "," . $user_one->s_longitude . "&destinations=" . $user_one->d_latitude . "," . $user_one->d_longitude . "&mode=driving&sensor=false&key=AIzaSyBSxfcR__-NJzR8i7HRM50WKAxIVBzrtYo";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $result = curl_exec($ch);
                curl_close($ch);
                $response = json_decode($result, true);
                $time = $response['rows'][0]['elements'][0]['duration']['text']; */

                $Provider = $user_one->provider;
                $Providersss = Provider::where('id', $Provider->id)->where('status', 'approved')->whereHas('service', function ($service) {
                    $service->where('status', 'active');
                })
                    ->whereRaw("(1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                    ->get(['id', 'latitude', 'longitude']);
                $response = [
                    'data' => $Providersss,
                   //'time' => $time
                ];
                return response()->json($response);
            }

            $response = [
                'data' => $Providers,
            ];

            return response()->json($response);
        } catch (Exception $e) {
            //
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')], 500);
            } else {
                return back()->with('flash_error', 'Something went wrong while sending request. Please try again.');
            }
        }
    }


    /**
     * Forgot Password.
     *
     * @return JsonResponse
     */


    public function forgot_password(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|email', //|exists:users,email
            'lang' => 'nullable|in:ar,en',
        ]);

        try {

            $user = User::where('email', $request->email)->first();
            if ($user) {
                $otp = mt_rand(100000, 999999);

                $user->otp = $otp;
                $user->save();

                Notification::send($user, new ResetPasswordOTP($otp));

                return response()->json([
                    'message' => 'OTP sent to your email!',
                    'user' => $user
                ]);
            } else {
//                return response()->json(['message' => trans('api.Email Not Found Ar')], 422);
                if ($request->lang == "ar") {
                    return response()->json(['message' => trans("api.Email Not Found Ar")], 422);
                } elseif ($request->lang == "en") {
                    return response()->json(['message' => trans("api.Email Not Found En")], 422);
                } else {
                    return response()->json(['message' => trans("api.Email Not Found Ar")], 422);
                }
            }
        } catch (Exception $e) {
//            return response()->json(['error' => trans('api.something_went_wrong')], 500);
            if ($request->lang == "ar") {
                return response()->json(['message' => trans("api.Something Went Wrong Ar")], 500);
            } elseif ($request->lang == "en") {
                return response()->json(['message' => trans("api.Something Went Wrong En")], 500);
            } else {
                return response()->json(['message' => trans("api.Something Went Wrong Ar")], 500);
            }
        }
    }
//
//    public
//    function forgetPasswordUser(Request $request)
//    {
//        $validator = validator()->make($request->all(), [
//            'email' => 'required',
//        ]);
//
//        if ($validator->fails()) {
//            return responseJson(0, $validator->errors()->first(), $validator->errors());
//        }
//        $user = User::where('email', $request->email)->first();
//        if ($user) {
//            $code = rand(1111, 9999);
//            $update = $user->update(['code_reset' => $code]);
//            if ($update) {
//
//                Mail::to($user->email)
//                    ->bcc('ailbaza156@gmail.com')
//                    ->send(new ResetPasswordUser($code));
//                return responseJson(1, 'برجاء فحص الايميل الخاص بك', $user);
//            } else {
//                return responseJson(0, 'حدث خطا . حاول مره اخرى');
//            }
//        } else {
//            return responseJson(0, 'بيانات الدخول غير صحيحه');
//        }
//    }

    /**
     * Reset Password.
     *
     * @return JsonResponse
     */

    public function reset_password(Request $request)
    {

        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
            'id' => 'required|numeric|exists:users,id',
            'lang' => 'nullable|in:ar,en',
        ]);

        try {

            $User = User::findOrFail($request->id);
            $User->password = bcrypt($request->password);
            $User->save();

            if ($request->ajax()) {
//                return response()->json(['message' => 'Password Updated']);
                if ($request->lang == "ar") {
                    return response()->json(['message' => trans('api.Password changed successfully! Ar')], 200);
                } elseif ($request->lang == "en") {
                    return response()->json(['message' => trans('api.Password changed successfully! En')], 200);
                } else {
                    return response()->json(['message' => trans('api.Password changed successfully! Ar')], 200);
                }
            }

        } catch (Exception $e) {
            if ($request->ajax()) {
                if ($request->lang == "ar") {
                    return response()->json(['message' => trans("api.Something Went Wrong Ar")], 422);
                } elseif ($request->lang == "en") {
                    return response()->json(['message' => trans("api.Something Went Wrong En")], 422);
                } else {
                    return response()->json(['message' => trans("api.Something Went Wrong Ar")], 422);
                }
            }
        }
    }

    /**
     * help Details.
     *
     * @return JsonResponse
     */

    public
    function help_details(Request $request)
    {

        try {

            if ($request->ajax()) {
                return response()->json([
                    'contact_number' => Setting::get('contact_number', ''),
                    'contact_email' => Setting::get('contact_email', '')
                ]);
            }

        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }
        }
    }

}
