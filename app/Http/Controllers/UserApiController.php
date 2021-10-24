<?php

namespace App\Http\Controllers;

use App\EmailUser;
use App\Http\Controllers\SendPushNotification;
use App\MobileUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;

use Auth;
use JWTAuth;
use Hash;
use Storage;
use Setting;
use Exception;
use Notification;

use Carbon\Carbon;
//use App\Http\Controllers\SendPushNotification;
use App\Notifications\ResetPasswordOTP;
use App\Helpers\Helper;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\Card;
use App\User;
use Config;
use App\Provider;
use App\Settings;
use App\Promocode;
use App\ServiceType;
use App\UserRequests;
use App\RequestFilter;
use App\PromocodeUsage;
use App\ProviderService;
use App\UserRequestRating;
use App\Http\Controllers\ProviderResources\TripController;


class UserApiController extends Controller
{

    public function __construct()
    {
        $this->client = Client::find(1);
    }

//    public function __construct()
//    {
//        $this->middleware('api'); //, ['except' => ['show', 'store', 'available', 'location_edit', 'location_update', 'checkAccount']]
//    }


    public function scheduleSearching(Request $request)
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
            'comment' => 'required',
        ]);

        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }

        $user_requests = UserRequests::findOrFail($request->trip_id); //trip

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
                    if ($rate) {
                        return responseJson(1, 'تمت العمليه بنجاح', $rate);
                    }
                    return responseJson(0, 'لا توجد بيانات');
                } else {
                    $rate = $user_requests->rating->update([
                        'user_comment' => $request->comment,
                        'user_rating' => $request->rating,
                    ]);

                    if ($rate) {
                        return responseJson(1, 'تمت العمليه بنجاح', $rate);
                    }
                    return responseJson(0, 'لا توجد بيانات');
                }
            } else {
                return responseJson(0, 'لا يمكنك التعليق على الرحله');
            }
        } else {
            return responseJson(0, 'هذا الطلب لم يكتمل بعد');
        }
    }


    public function providerAll(Request $request)
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
     * @return \Illuminate\Http\Response
     */
    public
    function authenticate(Request $request)
    {
        $this->validate($request, [
            'device_id' => 'required',
            'device_type' => 'required|in:android,ios',
            'device_token' => 'required',
            'username' => 'required',
            'password' => 'required|min:6',

        ]);

        $Useremail = User::where(['email' => $request->username])->orWhere(['mobile' => $request->username])->first()->email;
        $request['email'] = $Useremail;
        Config::set('auth.providers.users.model', 'App\User');
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'The mobile address or password you entered is incorrect.'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Something went wrong, Please try again later!'], 500);
        }
        $User = User::find(Auth::user()->id);
        $User->update(['status' => 'online']);

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
    }

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
    function signup(Request $request)
    {
        $this->validate($request, [
            'social_unique_id' => ['required_if:login_by,facebook,google', 'unique:users'],
            'device_type' => 'required|in:android,ios',
            'device_token' => 'required',
            'device_id' => 'required',
            'login_by' => 'required|in:manual,facebook,google',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'mobile' => 'required',
            'password' => 'required|min:6',
        ]);
        try {

            $User = $request->all();

            $User['payment_mode'] = 'CASH';
            $User['status'] = 'online';
            $User['password'] = bcrypt($request->password);
            $User = User::create($User);

//            return $User;

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
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public
    function checkAccount(Request $request)
    {
        try {
            if ($request->mobile) {
                $Provider = User::where(['mobile' => urlencode($request->mobile)])->get();
                if (count($Provider) > 0) {
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
        if ($user = User::find($request->id)) {
            $user->latitude = null;
            $user->longitude = null;
            $user->status = 'offline';
            $user->save();
        }
        try {
            User::where('id', $request->id)->update(['device_id' => '', 'device_token' => '']);
            return response()->json(['message' => trans('api.logout_success')]);
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public
    function change_password(Request $request)
    {

        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
            'old_password' => 'required',
        ]);

        $User = Auth::user();

        if (Hash::check($request->old_password, $User->password)) {
            $User->password = bcrypt($request->password);
            $User->save();

            if ($request->ajax()) {
                return response()->json(['message' => trans('api.user.password_updated')]);
            } else {
                return back()->with('flash_success', 'Password Updated');
            }

        } else {
            return response()->json(['error' => trans('api.user.incorrect_password')], 500);
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public
    function update_location(Request $request)
    {
        try {
            if ($user = User::find(Auth::user()->id)) {
                $user->latitude = $request->latitude;
                $user->longitude = $request->longitude;
                $user->save();
            }
        } catch (Exception $e) {
        }
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public
    function details(Request $request)
    {
        $this->validate($request, [
            'device_type' => 'in:android,ios',
        ]);

        try {
            if ($user = User::find(Auth::user()->id)) {

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
                // return response()->json(['user' => $user]);
                return $user;

            } else {
                return response()->json(['error' => trans('api.user.user_not_found')], 500);
            }
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return JsonResponse|RedirectResponse
     */

    public
    function update_profile(Request $request)
    {

        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'max:255',
            'email' => 'nullable|email|unique:users,email,' . Auth::user()->id,
            'mobile' => 'required|unique:users,mobile,' . Auth::user()->id,
            'picture' => 'mimes:jpeg,bmp,png',
        ]);

        try {

            $user = User::findOrFail(Auth::user()->id);

            if ($user->mobile != $request->mobile) {
                $usersMobile = MobileUser::where('mobile', '=', $request['mobile'])->first();
                if ($usersMobile != null) {
                    flash()->error(trans('user.The phone is already in use'));
                    return redirect()->back();
                }
            }

            if ($user->email != $request->email) {
                $emailUsers = EmailUser::where('email', '=', $request['email'])->first();
//                dd($emailUsers != null);
                if ($emailUsers != null) {
                    flash()->error(trans('user.The email is already in use'));
                    return redirect()->back();
                }
            }


            if ($request->has('first_name')) {
                $user->first_name =base64_encode($request->first_name);
            }

            if ($request->has('last_name')) {
                $user->last_name = base64_encode($request->last_name);
            }

            if ($request->has('email')) {
                $user->email = $request->email;
            }

            if ($request->has('mobile')) {
                $user->mobile = $request->mobile;
            }

//            if ($request->picture != "") {
//                Storage::delete($user->picture);
//                $user->picture = $request->picture->store('user/profile');
//            }

            if ($request->hasFile('picture')) {
                $picture = $request->picture;
                // . $picture->getClientOriginalName();
                $code = rand(111111111, 999999999);
                $picture_new_name = time().$code ."pp";
                $picture->move('uploads/user/', $picture_new_name);

                $user->picture = 'uploads/user/' . $picture_new_name;
                $user->save();
            }

            $user->save();

            if ($request->ajax()) {
                return response()->json($user);
            } else {
                return back()->with('flash_success', trans('api.user.profile_updated'));
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => trans('api.user.user_not_found')], 500);
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public
    function services()
    {

        if ($serviceList = ServiceType::all()) {
            return $serviceList;
        } else {
            return response()->json(['error' => trans('api.services_not_found')], 500);
        }

    }


    /**
     * Show the application dashboard.
     *
     * @return JsonResponse|RedirectResponse
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
            ->whereRaw("(6371 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
            ->whereHas('service', function ($query) use ($service_type) {
                $query->where('status', 'active');
                $query->where('service_type_id', $service_type);
            })
//            ->orderBy('distance')
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

        $UserRequest = UserRequests::create([
            'current_provider_id' => $Providers[0]->id,
            'service_type_id' => $request->service_type,
        ]);
//        dd($UserRequest);
        try {

            $details = "https://maps.googleapis.com/maps/api/directions/json?origin=" . $request->s_latitude . "," . $request->s_longitude . "&destination=" . $request->d_latitude . "," . $request->d_longitude . "&mode=driving&key=" . env('GOOGLE_MAP_KEY');

            $json = curl($details);

            $details = json_decode($json, TRUE);

            $route_key = '';
//            $UserRequest = UserRequests::create($request->all());
//            $UserRequest = new UserRequests;
            $UserRequest->booking_id = Helper::generate_booking_id();
            $UserRequest->user_id = Auth::user()->id;
            $UserRequest->current_provider_id = $Providers[0]->id;
            $UserRequest->service_type_id = $request->service_type;
            $UserRequest->payment_mode = $request->payment_mode;

            $UserRequest->status = 'SCHEDULED';

            $UserRequest->s_address_ar = $request->s_address ?: "";
            $UserRequest->d_address_ar = $request->d_address ?: "";

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
//            dd($UserRequest->schedule_at);

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


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */


    public
    function App_Details()
    {

        $App_Status = Setting::get('app_status', '1');
        $App_Name = Setting::get('site_title', 'Al Baz');
        $App_Icon = Setting::get('site_icon', 'http://ailbaz.com/public/logo-black.png');
        $App_Splash = Setting::get('splash', 'http://ailbaz.com/public/logo-black.png');
        $App_Logo = Setting::get('site_logo', 'http://ailbaz.com/public/logo-black.png');
        $App_Msg = Setting::get('app_msg', 'System is under Maintenance');
        $Phone_Number = Setting::get('contact_number', '');
        $Sos = Setting::get('sos_number', '');
        $Currency = Setting::get('currency', '');
        $Email = Setting::get('contact_email', '');
        $Interval_Time = Setting::get('interval_time', '3000');
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
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */

    public
    function trips()
    {

        if (User::find(Auth::user()->id)) {
            try {
                $UserRequests = UserRequests::UserTrips(Auth::user()->id)->get();
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
                            "&key=" . env('GOOGLE_MAP_KEY');
                    }
                }
                return $UserRequests;
            } catch (Exception $e) {
                return response()->json(['error' => trans('api.something_went_wrong')]);
            }

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
    function estimated_fare(Request $request)
    {

        $someJSON = '[{"name":"Jonathan Suh","gender":"male"},{"name":"William Philbin","gender":"male"},{"name":"Allison McKinnery","gender":"female"}]';
        $someArray = json_decode($someJSON, true);
        //return response()->$someArray;
        //return response()->json($someArray);
        //echo response()->json(["name"=>"ali"]);


        //Log::info('Estimate', $request->all());
        $this->validate($request, [
            's_latitude' => 'required|numeric',
            's_longitude' => 'required|numeric',
            'd_latitude' => 'required|numeric',
            'd_longitude' => 'required|numeric',
            'service_type' => 'required|numeric|exists:service_types,id',
        ]);


        try {

            $details = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $request->s_latitude . "," . $request->s_longitude . "&destinations=" . $request->d_latitude . "," . $request->d_longitude . "&mode=driving&sensor=false&key=" . env('GOOGLE_MAP_KEY');
//            dd($details);
            $json = curl($details);
            $details = json_decode($json, TRUE);

            //return response()->json($details);

            $meter = $details['rows'][0]['elements'][0]['distance']['value'];

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

            $Providers = Provider::whereIn('id', $ActiveProviders)
                ->where('status', 'approved')
                ->whereRaw("(1.609344 * 3956 * acos( cos( radians('$latitude') ) * cos( radians(latitude) ) * cos( radians(longitude) - radians('$longitude') ) + sin( radians('$latitude') ) * sin( radians(latitude) ) ) ) <= $distance")
                ->get();

            $surge = 0;

            if ($Providers->count() <= Setting::get('surge_trigger') && $Providers->count() > 0) {
                $surge_price = (Setting::get('surge_percentage') / 100) * $total;
                $total += $surge_price;
                $surge = 1;
            }

            return response()->json([
                'estimated_fare' => round($total, 2),
                'distance' => $meter,
                'time' => $time,
                'surge' => $surge,
                'surge' => $surge,
                'surge' => $surge,
                'surge_value' => '1.4X',
                'tax_price' => $tax_price,
                'base_price' => $service_type->fixed,
                'wallet_balance' => Auth::user()->wallet_balance
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public
    function trip_details(Request $request)
    {
        $this->validate($request, [
            'request_id' => 'required|integer|exists:user_requests,id',
        ]);
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
                        "&path=color:0x191919|weight:3|enc:" . $value->route_key .
                        "&key=" . env('GOOGLE_MAP_KEY');
                }
            }
            return $UserRequests;
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }

    /**
     * get all promo code.
     *
     * @return \Illuminate\Http\Response
     */

    public
    function promocodes()
    {
        try {
            $this->check_expiry();

            return PromocodeUsage::Active()
                ->where('user_id', Auth::user()->id)
                ->with('promocode')
                ->get();

        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
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
                    PromocodeUsage::where('promocode_id', $promo->id)->update(['status' => 'EXPIRED']);
                } else {
                    PromocodeUsage::where('promocode_id', $promo->id)->update(['status' => 'ADDED']);
                }
            }
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }


    /**
     * add promo code.
     *
     * @return \Illuminate\Http\Response
     */

    public
    function add_promocode(Request $request)
    {

        $this->validate($request, [
            'promocode' => 'required|exists:promocodes,promo_code',
        ]);

        try {

            $find_promo = Promocode::where('promo_code', $request->promocode)->first();

            if ($find_promo->status == 'EXPIRED' || (date("Y-m-d") > $find_promo->expiration)) {

                if ($request->ajax()) {

                    return response()->json([
                        'message' => trans('api.promocode_expired'),
                        'code' => 'promocode_expired'
                    ]);

                } else {
                    return back()->with('flash_error', trans('api.promocode_expired'));
                }

            } elseif (PromocodeUsage::where('promocode_id', $find_promo->id)->where('user_id', Auth::user()->id)->where('status', 'ADDED')->count() > 0) {

                if ($request->ajax()) {

                    return response()->json([
                        'message' => trans('api.promocode_already_in_use'),
                        'code' => 'promocode_already_in_use'
                    ]);

                } else {
                    return back()->with('flash_error', 'Promocode Already in use');
                }

            } else {

                $promo = new PromocodeUsage;
                $promo->promocode_id = $find_promo->id;
                $promo->user_id = Auth::user()->id;
                $promo->status = 'ADDED';
                $promo->save();

                if ($request->ajax()) {

                    return response()->json([
                        'message' => trans('api.promocode_applied'),
                        'code' => 'promocode_applied'
                    ]);

                } else {
                    return back()->with('flash_success', trans('api.promocode_applied'));
                }
            }

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
     * @return \Illuminate\Http\Response
     */

    public
    function upcoming_trips()
    {

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
                        "&key=" . env('GOOGLE_MAP_KEY');
                }
            }
            return $UserRequests;
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public
    function upcoming_trip_details(Request $request)
    {

        $this->validate($request, [
            'request_id' => 'required|integer|exists:user_requests,id',
        ]);

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
                        "&key=" . env('GOOGLE_MAP_KEY');
                }
            }
            return $UserRequests;
        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')]);
        }
    }


    /**
     * Show the nearby providers.
     *
     * @return \Illuminate\Http\Response
     */

    public
    function show_providers(Request $request)
    {
        $this->validate($request, [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'service' => 'numeric|exists:service_types,id'
        ]);
        try {
            // return response()->json([['sd'=>$request->provider_id]]);
            // if($request->provider_id > 0){
            //     return Provider::where(['id' => $request->provider_id])->get();
            // }
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
            $response = [
                'data' => $Providers,
            ];
            return response()->json($response);
        } catch (Exception $e) {
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
     * @return \Illuminate\Http\Response
     */


    public
    function forgot_password(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
        ]);

        try {

            $user = User::where('email', $request->email)->first();

            $otp = mt_rand(100000, 999999);

            $user->otp = $otp;
            $user->save();

            Notification::send($user, new ResetPasswordOTP($otp));

            return response()->json([
                'message' => 'OTP sent to your email!',
                'user' => $user
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => trans('api.something_went_wrong')], 500);
        }
    }


    /**
     * Reset Password.
     *
     * @return \Illuminate\Http\Response
     */

    public
    function reset_password(Request $request)
    {

        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
            'id' => 'required|numeric|exists:users,id'
        ]);

        try {

            $User = User::findOrFail($request->id);
            $User->password = bcrypt($request->password);
            $User->save();

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
     * help Details.
     *
     * @return \Illuminate\Http\Response
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
