<?php

namespace App\Http\Controllers\ProviderAuth;

use App\Car;
use App\CarModel;
use App\CarProvider;
use App\EmailProvider;
use App\ProviderProfile;
use App\Fleet;
use App\Admin;
use App\Helpers\Helper;
use App\Http\Controllers\ProviderResources\ProfileController;
use App\Mail\alBazAccountConfirmation;
use App\Mail\FleetConfirmationCar;
use App\Mail\ProviderAccountConfirmation;
use App\Mail\ProviderConfirmationCar;
use App\Mail\ResetPasswordUser;
use App\MobileProvider;
use App\ServiceType;
use App\UserRequestPayment;
use App\UserRequests;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Notifications\ResetPasswordOTP;

use Auth;
use Config;
use JWTAuth;
use Setting;
use Notification;
use Validator;
use Socialite;
use App\Provider;
use App\ProviderDevice;
use App\ProviderService;

class TokenController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\JsonResponse|void
     */

    public function register(Request $request){

        $validator = validator()->make($request->all(), [
            'device_mac' => 'required',
            'device_id' => 'required',
            'device_type' => 'required|in:android,ios',
            'device_token' => 'required',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:providers,email',
            'mobile' => 'required|unique:providers,mobile',
            'password' => 'required|min:6',
            'licenceType'=>'required',
            'nationalId'=>'required|min:14|max:14',
            'lang'=>'required',
            'selectedGovernmentCode' => 'required|exists:governorates,id',
        ]);

        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
        }
        //ProfileController::checkData();
        try {

            $Provider = $request->all();
            $Provider['password'] = bcrypt($request->password);
            // $Provider['identity_number'] = $request->nationalId;
            // $Provider['car_license_type'] = $request->licenceType;

            //$Provider['first_name'] = base64_encode($request->first_name);
            //$Provider['last_name'] = base64_encode($request->last_name);
            //dd($Provider['first_name']);
            $Provider['id_url'] = str_random(100);
            $Provider = Provider::create($Provider);

            $Provider->register_from="andriod";

            $Provider->governorate_id= $request->selectedGovernmentCode;
            $Provider->identity_number= $request->nationalId;
            $Provider->car_license_type = $request->licenceType;
            $emailProvider = EmailProvider::create([
                'provider_id' => $Provider->id,
                'email' => $request->email,
            ]);

            $mobileProvider = MobileProvider::create([
                'provider_id' => $Provider->id,
                'mobile' => $request->mobile,
            ]);

            $provider_profle = ProviderProfile::create([
                'provider_id' => $Provider->id,
                'language' => $request->lang,
                'address'=>"..",
                'address_secondary'=>'..',
                'city'=>'..',
                'country'=>'--',
                'postal_code'=>111111111111111111,
            ]);

            //if (Setting::get('app_status', 0) == 1) {
            $Provider->update(['status' => 'onboarding']);
                //$serviceType = ServiceType::first();
                //ProviderService::create([
                    //'provider_id' => $Provider->id,
                    //'service_type_id' => $serviceType->id,
                    //'status' => 'active',
                //]);
            //}

            ProviderDevice::create([
                'provider_id' => $Provider->id,
                'udid' => $request->device_id,
                'token' => $request->device_token,
                'type' => $request->device_type,
            ]);
            if ($Provider) {

                //return responseJson(422, trans('api.Please check your email'), $Provider);
                return responseJson(200, trans('Done'));


            } else {
                return responseJson(0, 'بيانات الدخول غير صحيحه');
            }
        } catch (QueryException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Something went wrong, Please try again later!'], 500);
            }
            return abort(500);
        }
    }

    public function CarTypeTrue(Request $request){
        //return responseJson(422, $validator->errors()->first(), $validator->errors());

        $validator = validator()->make($request->all(), [
            //'provider_id' => 'required|numeric|exists:providers,id',
            'email' => 'required|exists:providers,email',
            'car_code' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
        }
        //return "Aloo" ;
        $provider = Provider::where(['email' => $request->email])->first();
        //return $provider->email ;
        $car = Car::where('car_code', $request->car_code)->with('providers')->first();
        //return $car ;
        $provider_car = Provider::where('car_id', $car->id)->first();
        //return $provider_car;
        //return $provider->id ;
        $fleet = Fleet::findOrFail($car->fleet_id);
        //return $fleet ;
        // if (count($car) > 0) {
        if ($car != null) {
            //return $provider->id ;
            $provider_services = ProviderService::where('provider_id', $provider->id)->count();

            if ($provider_services == 0) {
                $provider_service = ProviderService::create([
                    'provider_id' => $provider->id,
                    'service_type_id' => $car->carModel->service_id,
                    'status' => 'offline',
                ]);
            }
            $car->providers()->attach($provider->id, ['status' => 'not_active']);

            $code = rand(111111, 999999);
            $ids = $provider->id;

            $update = $provider->update([
                'otp' => $code,
            ]);

            if ($update) {
                // return "Aloo" ;
                if ($car->fleet_id != null) {
                    // Mail::to($fleet->email)
                    //     ->bcc(Admin::find(28)->email)
                    //     ->send(new FleetConfirmationCar($fleet, $car, $provider));

                        $admin=Admin::find(28);
                        config(['mail.username' => $admin->email]);
                        config(['mail.password'=>$admin->email_password]);
                        //dd( $admin->email, $admin->email_password);

                        Mail::to($fleet->email)
                        ->bcc('ailbaza156@gmail.com')
                        //->bcc(Admin::find(28)->email)
                        ->send(new FleetConfirmationCar($fleet, $car, $provider));

                    // Mail::to($provider->email)
                    //     ->bcc(Admin::find(28)->email)
                    //     ->send(new ProviderAccountConfirmation($code, $ids));

                    $admin=Admin::find(28);
                        config(['mail.username' => $admin->email]);
                        config(['mail.password'=>$admin->email_password]);
                        //dd( $admin->email, $admin->email_password);

                        Mail::to($provider->email)
                        ->bcc('ailbaza156@gmail.com')
                        //->bcc(Admin::find(28)->email)
                        ->send(new ProviderAccountConfirmation($code, $ids));

                    return responseJson(200, trans('api.Please check your email'), $car);
                } else {
                    // if (count($provider_car) != null) {
                    if ($provider_car != null) {
                        // Mail::to($provider_car->email)
                        //     ->bcc(Admin::find(28)->email)
                        //     ->send(new ProviderConfirmationCar($provider_car, $car, $provider));

                        $admin=Admin::find(28);
                        config(['mail.username' => $admin->email]);
                        config(['mail.password'=>$admin->email_password]);
                        //dd( $admin->email, $admin->email_password);

                        Mail::to($provider_car->email)
                        ->bcc('ailbaza156@gmail.com')
                        //->bcc(Admin::find(28)->email)
                        ->send(new ProviderConfirmationCar($provider_car, $car, $provider));

                    }
                    // Mail::to($provider->email)
                    //     ->bcc(Admin::find(28)->email)
                    //     ->send(new ProviderAccountConfirmation($code, $ids));

                    $admin=Admin::find(28);
                    config(['mail.username' => $admin->email]);
                    config(['mail.password'=>$admin->email_password]);
                    //dd( $admin->email, $admin->email_password);

                    Mail::to($provider->email)
                    ->bcc('ailbaza156@gmail.com')
                    //->bcc(Admin::find(28)->email)
                    ->send(new ProviderAccountConfirmation($code, $ids));

                    return responseJson(200, trans('api.Please check your email'), $car);
                }
            } else {
                return response()->json(['error' => trans('admin.Something Went Wrong')], 422);
            }
        } else {
            return response()->json(['error' => trans('admin.Car Code Not Found')], 422);
        }
    }

    public function CarTypeFalse(Request $request){

        $validator = validator()->make($request->all(), [
            'car_id' => 'nullable|numeric|exists:cars,id',
            'email' => 'nullable|exists:providers,email',
            'service_type_id' => 'nullable',
            'car_number' => 'nullable|min:6|max:10|unique:cars,car_number',
            'car_color' => 'nullable',
            'car_front' => 'required',
            'car_back' => 'required',
            'car_left' => 'required',
            'car_right' => 'required',
            'car_licence_front' => 'required',
            'car_licence_back' => 'required',
        ]);

        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
        }

        if ($request->car_id == null) {
            //$provider = Provider::findOrFail($request->provider_id);
            $provider = Provider::where(['email' => $request->email])->first();
            $providerService = CarModel::findOrFail($request->service_type_id);

            $car_code = mt_rand(1000000000, 9999999999);
            $provider_services = ProviderService::where('provider_id', $provider->id)->count();
            if ($provider_services == 0) {
                $provider_service = ProviderService::create([
                    'provider_id' => $provider->id,
                    'service_type_id' => $providerService->service_id,
                    'status' => 'offline',
                ]);
            }
            $cars = Car::create([
                'car_code' => $car_code,
                'car_number' => $request->car_number,
                'car_model_id' => $providerService->id,
                'color' => $request->car_color,
            ]);
            $cars->providers()->attach($provider->id);

            $update = $provider->update([
                'car_id' => $cars->id,
            ]);

            // if (!empty($request->car_front)) $cars->car_front = Helper::upload_picture($request->file('car_front'));
            // if (!empty($request->car_back)) $cars->car_back = Helper::upload_picture($request->file('car_back'));
            // if (!empty($request->car_left)) $cars->car_left = Helper::upload_picture($request->file('car_left'));
            // if (!empty($request->car_right)) $cars->car_right = Helper::upload_picture($request->file('car_right'));
            // if (!empty($request->car_licence_front)) $cars->car_licence_front = Helper::upload_picture($request->file('car_licence_front'));
            // if (!empty($request->car_licence_back)) $cars->car_licence_back = Helper::upload_picture($request->file('car_licence_back'));

            // if (!empty($request->car_front)) $car->car_front = Helper::upload_picture($request->file('car_front'));
            if (!empty($request->car_front)){
                $picture = $request->car_front;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."cf";
                $picture->move('uploads/car/', $avatar_new_name);
                $cars->car_front ='uploads/car/' . $avatar_new_name;
            }
            // if (!empty($request->car_back)) $car->car_back = Helper::upload_picture($request->file('car_back'));
            if (!empty($request->car_back)){
                $picture = $request->car_back;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."cb";
                $picture->move('uploads/car/', $avatar_new_name);
                $cars->car_back ='uploads/car/' . $avatar_new_name;
            }
            // if (!empty($request->car_left)) $car->car_left = Helper::upload_picture($request->file('car_left'));
            if (!empty($request->car_left)){
                $picture = $request->car_left;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."cf";
                $picture->move('uploads/car/', $avatar_new_name);
                $cars->car_left ='uploads/car/' . $avatar_new_name;
            }
            //if (!empty($request->car_right)) $car->car_right = Helper::upload_picture($request->file('car_right'));
            if (!empty($request->car_right)){
                $picture = $request->car_right;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."cr";
                $picture->move('uploads/car/', $avatar_new_name);
                $cars->car_right ='uploads/car/' . $avatar_new_name;
            }
            // if (!empty($request->car_licence_front)) $car->car_licence_front = Helper::upload_picture($request->file('car_licence_front'));
            if (!empty($request->car_licence_front)){
                $picture = $request->car_licence_front;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."clf";
                $picture->move('uploads/car/', $avatar_new_name);
                $cars->car_licence_front ='uploads/car/' . $avatar_new_name;
            }
            // if (!empty($request->car_licence_back)) $car->car_licence_back = Helper::upload_picture($request->file('car_licence_back'));
            if (!empty($request->car_licence_back)){
                $picture = $request->car_licence_back;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."clb";
                $picture->move('uploads/car/', $avatar_new_name);
                $cars->car_licence_back ='uploads/car/' . $avatar_new_name;
            }

            $cars->save();

            $code = rand(111111, 999999);
            $ids = $provider->id;
            $update = $provider->update([
                'otp' => $code,
            ]);
            if ($update) {

                // Mail::to($provider->email)
                //     ->bcc(Admin::find(28)->email)
                //     ->send(new ProviderAccountConfirmation($code, $ids));

                $admin=Admin::find(28);
                config(['mail.username' => $admin->email]);
                config(['mail.password'=>$admin->email_password]);
                //dd( $admin->email, $admin->email_password);

                Mail::to($provider->email)
                ->bcc('ailbaza156@gmail.com')
                //->bcc(Admin::find(28)->email)
                ->send(new ProviderAccountConfirmation($code, $ids));

                return responseJson(200, trans('api.Please check your email'), $cars);
            } else {
                return response()->json(['error' => trans('admin.Something Went Wrong')], 422);
            }
        } elseif ($request->car_id != null) {
            $car = Car::findOrFail($request->car_id);

            // if (!empty($request->car_front)) $car->car_front = Helper::upload_picture($request->file('car_front'));
            // if (!empty($request->car_back)) $car->car_back = Helper::upload_picture($request->file('car_back'));
            // if (!empty($request->car_left)) $car->car_left = Helper::upload_picture($request->file('car_left'));
            // if (!empty($request->car_right)) $car->car_right = Helper::upload_picture($request->file('car_right'));
            // if (!empty($request->car_licence_front)) $car->car_licence_front = Helper::upload_picture($request->file('car_licence_front'));
            // if (!empty($request->car_licence_back)) $car->car_licence_back = Helper::upload_picture($request->file('car_licence_back'));

            // if (!empty($request->car_front)) $car->car_front = Helper::upload_picture($request->file('car_front'));
            if (!empty($request->car_front)){
                $picture = $request->car_front;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."cf";
                $picture->move('uploads/car/', $avatar_new_name);
                $car->car_front ='uploads/car/' . $avatar_new_name;
            }
            // if (!empty($request->car_back)) $car->car_back = Helper::upload_picture($request->file('car_back'));
            if (!empty($request->car_back)){
                $picture = $request->car_back;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."cb";
                $picture->move('uploads/car/', $avatar_new_name);
                $car->car_back ='uploads/car/' . $avatar_new_name;
            }
            // if (!empty($request->car_left)) $car->car_left = Helper::upload_picture($request->file('car_left'));
            if (!empty($request->car_left)){
                $picture = $request->car_left;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."cf";
                $picture->move('uploads/car/', $avatar_new_name);
                $car->car_left ='uploads/car/' . $avatar_new_name;
            }
            //if (!empty($request->car_right)) $car->car_right = Helper::upload_picture($request->file('car_right'));
            if (!empty($request->car_right)){
                $picture = $request->car_right;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."cr";
                $picture->move('uploads/car/', $avatar_new_name);
                $car->car_right ='uploads/car/' . $avatar_new_name;
            }
            // if (!empty($request->car_licence_front)) $car->car_licence_front = Helper::upload_picture($request->file('car_licence_front'));
            if (!empty($request->car_licence_front)){
                $picture = $request->car_licence_front;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."clf";
                $picture->move('uploads/car/', $avatar_new_name);
                $car->car_licence_front ='uploads/car/' . $avatar_new_name;
            }
            // if (!empty($request->car_licence_back)) $car->car_licence_back = Helper::upload_picture($request->file('car_licence_back'));
            if (!empty($request->car_licence_back)){
                $picture = $request->car_licence_back;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."clb";
                $picture->move('uploads/car/', $avatar_new_name);
                $car->car_licence_back ='uploads/car/' . $avatar_new_name;
            }

            $car->save();

            return responseJson(200, trans('admin.editMessageSuccess'), $car);
        }
    }


    public function confirm_car($provider_car_id, $provider_id, $car_id){

        $provider_car = Provider::findOrFail($provider_car_id);
        $provider = Provider::findOrFail($provider_id);
        $car = Car::findOrFail($car_id);

        $car_provider = CarProvider::where('provider_id', $provider->id)->where('car_id', $car->id)->first();
        $car_provider->update(['status' => 'active']);
        //return redirect('/');
        return "Done";
    }

    public function confirm_car_not($provider_car_id, $provider_id, $car_id) {

        $provider_car = Provider::findOrFail($provider_car_id);
        $provider = Provider::findOrFail($provider_id);
        $car = Car::findOrFail($car_id);

        $car_provider = CarProvider::where('provider_id', $provider->id)->where('car_id', $car->id)->first();
        $car_provider->update(['status' => 'not_active']);
        // return redirect('/');
        return "Done";
    }


    public function fleet_confirm_car($fleet_id, $provider_id, $car_id){

        $fleet = Fleet::findOrFail($fleet_id);
        $provider = Provider::findOrFail($provider_id);
        $car = Car::findOrFail($car_id);

        $car_provider = CarProvider::where('provider_id', $provider->id)->where('car_id', $car->id)->first();
        $car_provider->update(['status' => 'active']);
        return redirect('/');
    }

    public function fleet_confirm_car_not($fleet_id, $provider_id, $car_id){

        $fleet = Fleet::findOrFail($fleet_id);
        $provider = Provider::findOrFail($provider_id);
        $car = Car::findOrFail($car_id);

        $car_provider = CarProvider::where('provider_id', $provider->id)->where('car_id', $car->id)->first();
        $car_provider->update(['status' => 'not_active']);
        return redirect('/');
    }

    public function update_image(Request $request){

        $validator = validator()->make($request->all(), [
            'car_id' => 'nullable|numeric|exists:cars,id',
        ]);

        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
        }

        $car = Car::findOrFail($request->car_id);

        // if (!empty($request->car_front)) $car->car_front = Helper::upload_picture($request->file('car_front'));
        if (!empty($request->car_front)){
            $picture = $request->car_front;
            $code = rand(111111111, 999999999);
            $avatar_new_name=time().$code ."cf";
            $picture->move('uploads/car/', $avatar_new_name);
            $car->car_front ='uploads/car/' . $avatar_new_name;
        }
        // if (!empty($request->car_back)) $car->car_back = Helper::upload_picture($request->file('car_back'));
        if (!empty($request->car_back)){
            $picture = $request->car_back;
            $code = rand(111111111, 999999999);
            $avatar_new_name=time().$code ."cb";
            $picture->move('uploads/car/', $avatar_new_name);
            $car->car_back ='uploads/car/' . $avatar_new_name;
        }
        // if (!empty($request->car_left)) $car->car_left = Helper::upload_picture($request->file('car_left'));
        if (!empty($request->car_left)){
            $picture = $request->car_left;
            $code = rand(111111111, 999999999);
            $avatar_new_name=time().$code ."cf";
            $picture->move('uploads/car/', $avatar_new_name);
            $car->car_left ='uploads/car/' . $avatar_new_name;
        }
        //if (!empty($request->car_right)) $car->car_right = Helper::upload_picture($request->file('car_right'));
        if (!empty($request->car_right)){
            $picture = $request->car_right;
            $code = rand(111111111, 999999999);
            $avatar_new_name=time().$code ."cr";
            $picture->move('uploads/car/', $avatar_new_name);
            $car->car_right ='uploads/car/' . $avatar_new_name;
        }
        // if (!empty($request->car_licence_front)) $car->car_licence_front = Helper::upload_picture($request->file('car_licence_front'));
        if (!empty($request->car_licence_front)){
            $picture = $request->car_licence_front;
            $code = rand(111111111, 999999999);
            $avatar_new_name=time().$code ."clf";
            $picture->move('uploads/car/', $avatar_new_name);
            $car->car_licence_front ='uploads/car/' . $avatar_new_name;
        }
        // if (!empty($request->car_licence_back)) $car->car_licence_back = Helper::upload_picture($request->file('car_licence_back'));
        if (!empty($request->car_licence_back)){
            $picture = $request->car_licence_back;
            $code = rand(111111111, 999999999);
            $avatar_new_name=time().$code ."clb";
            $picture->move('uploads/car/', $avatar_new_name);
            $car->car_licence_back ='uploads/car/' . $avatar_new_name;
        }

        $car->save();

        return responseJson(200, trans('admin.editMessageSuccess'), $car);
    }

    public function verificationCode(Request $request){

        $validator = validator()->make($request->all(), [
            'id' => 'required|exists:providers,id', //
            'code' => 'required|numeric',
            'device_mac' => 'required',
        ]);
        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
        }
        $provider = Provider::findOrFail($request->id);
        if ($provider) {
            if ($provider->device_mac == $request->device_mac) {
                if ($provider->otp == $request->code) {
                    if ($provider->otp != 0) {
                        $provider->otp = 0;
                        $provider->save();
                        return response()->json(true);
                    } else {
                        return responseJson(422, trans('api.Code it cannot be equal to Zero'));
                    }
                } else {
                    return response()->json(false);
                }
            } else {
                return response()->json(['error' => trans('api.no')], 401);
            }
        } else {
            return responseJson(422, trans('api.providerNotFound'));
        }
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */

    public function login_d(Request $request) {

        $validator = validator()->make($request->all(), [
            'lang' => 'nullable|in:ar,en',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
        }
        Config::set('auth.providers.users.model', 'App\Provider');
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            if ($request->lang == "ar") {
                return response()->json(['message' => trans('api.The email address or password you entered is incorrect. Ar')], 406);
            } elseif ($request->lang == "en") {
                return response()->json(['message' => trans('api.The email address or password you entered is incorrect. En')], 406);
            } else {
                return response()->json(['message' => trans('api.The email address or password you entered is incorrect. Ar')], 406);
            }
        }
        return $token;

    }
    public function authenticate(Request $request){

        $validator = validator()->make($request->all(), [
            'lang' => 'nullable|in:ar,en',
            'device_id' => 'required',
            'device_type' => 'required|in:android,ios',
            'device_token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'device_mac' => 'required',
        ]);
        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
        }
        Config::set('auth.providers.users.model', 'App\Provider');
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                if ($request->lang == "ar") {
                    return response()->json(['message' => trans('api.The email address or password you entered is incorrect. Ar')], 406);
                } elseif ($request->lang == "en") {
                    return response()->json(['message' => trans('api.The email address or password you entered is incorrect. En')], 406);
                } else {
                    return response()->json(['message' => trans('api.The email address or password you entered is incorrect. Ar')], 406);
                }
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Something went wrong, Please try again later!'], 500);
        }

        $provider = Provider::with('service', 'device')->find(Auth::user()->id);
        if ($provider['status'] != 'onboarding') {
            if ($provider['otp'] == 0) {
                $provider->update([
                    'device_mac' => $request->device_mac,
                ]);

                $provider->access_token = $token;
                $provider->currency = Setting::get('currency', '$');
                $provider->sos = Setting::get('sos_number', '911');
                if ($provider->device) {
                    ProviderDevice::where('id', $provider->device->id)->update([
                        'udid' => $request->device_id,
                        'token' => $request->device_token,
                        'type' => $request->device_type,
                    ]);

                } else {
                    ProviderDevice::create([
                        'provider_id' => $provider->id,
                        'udid' => $request->device_id,
                        'token' => $request->device_token,
                        'type' => $request->device_type,
                    ]);
                }

                return response()->json($provider);
            } else {
                return responseJson(420, trans('api.The email has not been confirmed yet'));
            }
        } else {
            return response()->json('Not Active');
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function logout(Request $request){

        try {
            ProviderDevice::where('provider_id', $request->id)->update(['udid' => '', 'token' => '']);
            ProviderService::where('provider_id', $request->id)->update(['status' => 'offline']);
            return response()->json(['message' => trans('api.logout_success')]);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Forgot Password.
     *
     * @return \Illuminate\Http\JsonResponse
     */


    public function forgot_password(Request $request){

        $validator = validator()->make($request->all(), [
            'email' => 'required|email|exists:providers,email',
        ]);
        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
        }
        try {

            $provider = Provider::where('email', $request->email)->first();

            $otp = mt_rand(100000, 999999);
            ProfileController::checkData();
            $provider->otp = $otp;
            $provider->save();

            Notification::send($provider, new ResetPasswordOTP($otp));

            return response()->json(['message' => 'OTP sent to your email!', 'provider' => $provider]);

        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }


    /**
     * Reset Password.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function reset_password(Request $request){

        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
            'id' => 'required|numeric|exists:providers,id'
        ]);

        try {
            ProfileController::checkData();
            $Provider = Provider::findOrFail($request->id);
            $Provider->password = bcrypt($request->password);
            $Provider->otp = 0;
            $Provider->save();

            if ($request->ajax()) {
                return response()->json(['message' => 'Password Updated']);
            }

        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function facebookViaAPI(Request $request){

        $validator = Validator::make(
            $request->all(),
            [
                'device_type' => 'required|in:android,ios',
                'device_token' => 'required',
                'accessToken' => 'required',
                'device_id' => 'required',
                'login_by' => 'required|in:manual,facebook,google'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->messages()->all()]);
        }
        $user = Socialite::driver('facebook')->stateless();
        $FacebookDrive = $user->userFromToken($request->accessToken);

        try {
            $FacebookSql = Provider::where('social_unique_id', $FacebookDrive->id);
            if ($FacebookDrive->email != "") {
                $FacebookSql->orWhere('email', $FacebookDrive->email);
            }
            $AuthUser = $FacebookSql->first();
            if ($AuthUser) {
                $AuthUser->social_unique_id = $FacebookDrive->id;
                $AuthUser->login_by = "facebook";
                $AuthUser->save();
            } else {
                $AuthUser["email"] = $FacebookDrive->email;
                $name = explode(' ', $FacebookDrive->name, 2);
                $AuthUser["first_name"] = $name[0];
                $AuthUser["last_name"] = isset($name[1]) ? $name[1] : '';
                $AuthUser["password"] = bcrypt($FacebookDrive->id);
                $AuthUser["social_unique_id"] = $FacebookDrive->id;
                $AuthUser["avatar"] = $FacebookDrive->avatar;
                $AuthUser["login_by"] = "facebook";
                $AuthUser = Provider::create($AuthUser);

                if (Setting::get('demo_mode', 0) == 1) {
                    $AuthUser->update(['status' => 'approved']);
                    ProviderService::create([
                        'provider_id' => $AuthUser->id,
                        'service_type_id' => '1',
                        'status' => 'active',
                        'service_number' => '4pp03ets',
                        'service_model' => 'Audi R8',
                    ]);
                }
            }
            if ($AuthUser) {
                $userToken = JWTAuth::fromUser($AuthUser);
                $User = Provider::with('service', 'device')->find($AuthUser->id);
                if ($User->device) {
                    ProviderDevice::where('id', $User->device->id)->update([

                        'udid' => $request->device_id,
                        'token' => $request->device_token,
                        'type' => $request->device_type,
                    ]);

                } else {
                    ProviderDevice::create([
                        'provider_id' => $User->id,
                        'udid' => $request->device_id,
                        'token' => $request->device_token,
                        'type' => $request->device_type,
                    ]);
                }
                return response()->json([
                    "status" => true,
                    "token_type" => "Bearer",
                    "access_token" => $userToken,
                    'currency' => Setting::get('currency', '$'),
                    'sos' => Setting::get('sos_number', '911')
                ]);
            } else {
                return response()->json(['status' => false, 'message' => "Invalid credentials!"]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => trans('api.something_went_wrong')]);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function googleViaAPI(Request $request){

        $validator = Validator::make(
            $request->all(),
            [
                'device_type' => 'required|in:android,ios',
                'device_token' => 'required',
                'accessToken' => 'required',
                'device_id' => 'required',
                'login_by' => 'required|in:manual,facebook,google'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->messages()->all()]);
        }
        $user = Socialite::driver('google')->stateless();
        $GoogleDrive = $user->userFromToken($request->accessToken);

        try {
            $GoogleSql = Provider::where('social_unique_id', $GoogleDrive->id);
            if ($GoogleDrive->email != "") {
                $GoogleSql->orWhere('email', $GoogleDrive->email);
            }
            $AuthUser = $GoogleSql->first();
            if ($AuthUser) {
                $AuthUser->social_unique_id = $GoogleDrive->id;
                $AuthUser->login_by = "google";
                $AuthUser->save();
            } else {
                $AuthUser["email"] = $GoogleDrive->email;
                $name = explode(' ', $GoogleDrive->name, 2);
                $AuthUser["first_name"] = $name[0];
                $AuthUser["last_name"] = isset($name[1]) ? $name[1] : '';
                $AuthUser["password"] = ($GoogleDrive->id);
                $AuthUser["social_unique_id"] = $GoogleDrive->id;
                $AuthUser["avatar"] = $GoogleDrive->avatar;
                $AuthUser["login_by"] = "google";
                $AuthUser = Provider::create($AuthUser);

                if (Setting::get('demo_mode', 0) == 1) {
                    $AuthUser->update(['status' => 'approved']);
                    ProviderService::create([
                        'provider_id' => $AuthUser->id,
                        'service_type_id' => '1',
                        'status' => 'active',
                        'service_number' => '4pp03ets',
                        'service_model' => 'Audi R8',
                    ]);
                }
            }
            if ($AuthUser) {
                $userToken = JWTAuth::fromUser($AuthUser);
                $User = Provider::with('service', 'device')->find($AuthUser->id);
                if ($User->device) {
                    ProviderDevice::where('id', $User->device->id)->update([

                        'udid' => $request->device_id,
                        'token' => $request->device_token,
                        'type' => $request->device_type,
                    ]);

                } else {
                    ProviderDevice::create([
                        'provider_id' => $User->id,
                        'udid' => $request->device_id,
                        'token' => $request->device_token,
                        'type' => $request->device_type,
                    ]);
                }
                return response()->json([
                    "status" => true,
                    "token_type" => "Bearer",
                    "access_token" => $userToken,
                    'currency' => Setting::get('currency', '$'),
                    'sos' => Setting::get('sos_number', '911')
                ]);
            } else {
                return response()->json(['status' => false, 'message' => "Invalid credentials!"]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => trans('api.something_went_wrong')]);
        }
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function refresh_token(Request $request){

        Config::set('auth.providers.users.model', 'App\Provider');

        $Provider = Provider::with('service', 'device')->find(Auth::user()->id);

        try {
            if (!$token = JWTAuth::fromUser($Provider)) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }

        $Provider->access_token = $token;

        return response()->json($Provider);
    }
}
