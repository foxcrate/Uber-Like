<?php

namespace App\Http\Controllers\ProviderResources;

use App\CarClass;
use App\CarModel;
use App\CarProvider;
use App\Car;
use App\CompanySubscription;
use App\EmailProvider;
use App\Helpers\Helper;
use App\Mail\alBazAccountConfirmation;
use App\Promocode;
use App\PromocodeUsage;
use App\ProviderDevice;
use App\Settings;
use App\Otp;
use App\Revenue;
use App\ServiceType;
use App\User;
use App\Admin;
use App\UserRequestPayment;
use App\UserRequestRating;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Setting;
use Illuminate\Support\Facades\Storage;
use Exception;
use Carbon\Carbon;

use App\ProviderProfile;
use App\UserRequests;
use App\Provider;
use App\ProviderService;
use App\Fleet;

$provider_andriod_version=0;

class ProfileController extends Controller
{
    /**
     * Create a new user instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('provider.api', ['except' => ['show', 'store', 'available', 'location_edit',
            'location_update', 'checkAccount', 'cars', 'fawzyActiveEmail', 'fawzyVerificationEmail','activeEmail', 'verificationEmail', 'imageUpload',
            'checkEmail','post_andriod_version', 'checkPhone','get_andriod_version','calculatePayment','droppedBill', 'checkEmailPhone', 'requestPayment', 'createRequestPayment', 'updateRequestPayments', 'checkData', 'userTax','userTax2']]);
    }


    public function userTax(Request $request)
    {
        ProfileController::checkData();
        $validator = validator()->make($request->all(), [
            'Trip_id' => 'required|exists:user_requests,id',
        ]);
        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
        }

        $trip = UserRequests::findOrFail($request->Trip_id);
        $user = User::findOrFail($trip->user_id);
        $service_type = ServiceType::findOrFail($trip->service_type_id); // waiting
        $user->update(['wallet_balance' => $user->wallet_balance - $service_type->fixed ]);
        //echo($user->wallet_balance - $service_type->fixed);
        $provider = Provider::findOrFail($trip->provider_id);
        // if ($provider->fleet != 0) {
        //     $company = Fleet::findOrFail($provider->fleet);
        //     $company->update(['wallet_balance' => $company->wallet_balance + $service_type->fixed ]);
        //     return responseJson(200, trans('admin.editMessageSuccess'));
        // } else {
        $provider->update(['wallet_balance' => $provider->wallet_balance + $service_type->fixed ]);

        $requestPayments= new UserRequestPayment();
        $requestPayments->request_id=$request->Trip_id;
        $requestPayments->payment_mode="CASH";
        $requestPayments->fixed=$service_type->fixed;
        $requestPayments->distance=0;
        $requestPayments->wallet=$trip->use_wallet;
        $requestPayments->total=$service_type->fixed;
        $requestPayments->save();

        return responseJson(200, trans('admin.editMessageSuccess'));
        // }

    }

    public function userTax2(Request $request){

        ProfileController::checkData();
        $validator = validator()->make($request->all(), [
            'Trip_id' => 'required|exists:user_requests,id',
        ]);

        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
        }

        $trip = UserRequests::findOrFail($request->Trip_id);
        $user = User::findOrFail($trip->user_id);
        $service_type = ServiceType::findOrFail($trip->service_type_id); // waiting
        $user->update(['wallet_balance' => $user->wallet_balance - $service_type->fixed ]);

        $requestPayments= new UserRequestPayment();
        $requestPayments->request_id=$request->Trip_id;
        $requestPayments->payment_mode="CASH";
        $requestPayments->fixed=$service_type->fixed;
        $requestPayments->distance=0;
        $requestPayments->wallet=$trip->use_wallet;
        $requestPayments->total=$service_type->fixed;
        $requestPayments->save();

        return responseJson(200, trans('admin.editMessageSuccess'));

    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function checkEmail(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'email' => 'required|email|max:255|unique:email_providers,email',
        ]);
        ProfileController::checkData();
        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
        }
        return responseJson(200, true);
    }

    public function checkPhone(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'phone' => 'required|unique:mobile_providers,mobile',
        ]);
        ProfileController::checkData();
        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
        }
        return responseJson(200, true);
    }

    public function checkEmailPhone(Request $request)
    {
        // $request->email=""
        $validator = validator()->make($request->all(), [
            'email' => 'required|email|max:255|unique:email_providers,email',
            'mobile' => 'required|unique:mobile_providers,mobile',
            'nationalId'=>'required|numeric|unique:providers,identity_number',
        ]);
        ProfileController::checkData();
        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
            //return "Error";
        }else{
        return responseJson(200, true);
        }
        //return "true";
    }


    static function distance_between($lat1, $lon1, $lat2, $lon2, $unit)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }


    public function updateRequestPayments(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'Trip_id' => 'required|exists:user_requests,id',
            'paid' => 'required', // surge
        ]);

        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
        }
        try {
            $trip = UserRequests::findOrFail($request->Trip_id);
            $trip_payment = UserRequestPayment::where('request_id', $request->Trip_id)->first();
            //return $trip_payment->total;
            $total_dist = $trip_payment->total;
            $user = User::findOrFail($trip->user_id);
            $provider = Provider::findOrFail($trip->provider_id);
            $trip_payment->update([
                'surge' => $request->paid
            ]);

            if ($provider->fleet != 0) {

                $company = Fleet::findOrFail($provider->fleet);

                if ((float)$request->paid >= $total_dist) {
                    $wallet = (float)$request->paid - $total_dist;
                    $user_wallet = $wallet + $user->wallet_balance;
                    $company_wallet = -$wallet + $company->wallet_balance;

                    $user->update(['wallet_balance' => $user_wallet]);
                    $company->update(['wallet_balance' => $company_wallet]);
                    return responseJson(200, trans('admin.createMessageSuccess'), ['trip_payment' => $trip_payment]);
                }

                if ((float)$request->paid < $total_dist) {
                    $wallet = $total_dist - (float)$request->paid;
                    $user_wallet = -$wallet + $user->wallet_balance;
                    $company_wallet = $wallet + $company->wallet_balance;

                    $user->update([
                        'wallet_balance' => $user_wallet
                    ]);
                    $company->update([
                        'wallet_balance' => $company_wallet
                    ]);
                    return responseJson(200, trans('admin.createMessageSuccess'), ['trip_payment' => $trip_payment]);
                }
            } else {
                if ((float)$request->paid > $total_dist) {
                    $wallet = (float)$request->paid - $total_dist;
                    $user_wallet = $wallet + $user->wallet_balance;
                    $provider_wallet = -$wallet + $provider->wallet_balance;

                    $user->update([
                        'wallet_balance' => $user_wallet
                    ]);
                    $provider->update([
                        'wallet_balance' => $provider_wallet
                    ]);

                    return responseJson(200, trans('admin.createMessageSuccess'), [
                        'trip_payment' => $trip_payment
                    ]);
                }

                if ((float)$request->paid < $total_dist) {
                    $wallet = $total_dist - (float)$request->paid;
                    $user_wallet = -$wallet + $user->wallet_balance;
                    $provider_wallet = $wallet + $provider->wallet_balance;

                    $user->update([
                        'wallet_balance' => $user_wallet
                    ]);
                    $provider->update([
                        'wallet_balance' => $provider_wallet
                    ]);

                    return responseJson(200, trans('admin.createMessageSuccess'), [
                        'trip_payment' => $trip_payment
                    ]);
                }
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => trans('admin.Something Went Wrong')], 422);
        }

    }

    public function createRequestPayment(Request $request)
    {


        $validator = validator()->make($request->all(), [
            'Trip_id' => 'required|exists:user_requests,id',
        ]);

        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
        }

        $this->checkData();
        $trip = UserRequests::findOrFail((int)$request->Trip_id);
        $service_type = ServiceType::findOrFail($trip->service_type_id);
        $WaitingTime = $trip->started_at->diffInSeconds($trip->arrived_at) / 60 ;

        // if($WaitingTime < 5 ){
        //    $WaitingTime =0;
        // }
        // else{
        //     $WaitingTime =  $WaitingTime - 5;
        // }

        if($WaitingTime <= $service_type->waiting) {
            $WaitingTime = 0;
        }
        else {
            $WaitingTime = round($WaitingTime - $service_type->waiting);
        }

        $min_wait_price = $service_type->min_wait_price * ( $WaitingTime);


        $dist2 = $this->distance_between($trip->s_latitude, $trip->s_longitude, $trip->d_latitude, $trip->d_longitude, 'K');
        $dist = round((float)number_format((float)$dist2, 3, '.', ''));
        $user = User::findOrFail($trip->user_id);
        $wallet_user = 0;
        if ($user->wallet_balance < 0) {
            $wallet_user =  - $user->wallet_balance;
        //dd($wallet_user);
            $user->update([
                'wallet_balance' => 0
            ]);
        }

        //$price = $dist * $service_type->price;

        $time_trips = $trip->finished_at->diffInSeconds($trip->started_at);
        $time_trip =round($time_trips / 60);
        $tripTimePrice=$service_type->minute *$time_trip;

        $tax_percentage = Setting::get('tax_percentage', 10);

        if ($trip->use_wallet == 1) {
            // فى حاله ان كان مستخدم المحفظه
            $total_di = $service_type->price * $dist;
            $tax_percentage = Setting::get('tax_percentage', 10);
            $tax = (($total_di + $min_wait_price + $service_type->fixed) * $tax_percentage) / 100;
            $total_dis = $total_di + $min_wait_price + $service_type->fixed + $tax + $tripTimePrice;
            $total_dist = (float)number_format((float)$total_dis, 2, '.', '');
            $before_discount_total = $total_dist  ;

            $promoCodeUser = PromocodeUsage::where('user_id', $user->id)->where('status', '=', 'USED')->orderBy('created_at', 'desc')->first();
            $user->update([
                'total_price' => $user->total_price + $before_discount_total,
                'total_trips' => $user->total_trips + 1,
            ]);

            if ($promoCodeUser != null) {
                // فى حاله ان كان مستخدم برومو كود والمحفظه
                $promoCode = Promocode::findOrFail($promoCodeUser->promocode_id);
                $promoCodeUser->update([
                    'status' => 'EXPIRED',
                ]);
                //$discount = $total_dist / $promoCode->discount;
                $discount= $total_dist * ($promoCode->discount/100);
                $total = $total_dist - $discount;
                if ($user->wallet_balance >= $total) {
                    // فى حاله ان كان مستخدم برومو كود وان كان مستخدم المحفظه وان كانت الفلوس الى فى المحفظه اكبر او بتساوى فلوس الرحله
                    $total_wallet = $user->wallet_balance - $total;
                    $total_trip = $total_wallet - $user->wallet_balance;
                    if ($total_trip <= 0) {
                        $total_trip = 0;
                    }

                    $gift_percentage =(int)Setting::get('gift_percentage', 0);
                    $requestPayments = UserRequestPayment::create([
                        'request_id' => $request->Trip_id,
                        'payment_mode' => 'CASH',
                        'distance' => $dist,
                        'discount_wallet' => $total,
                        'WaitingTime' => $WaitingTime,
                        'WaitingPrice' => $service_type->min_wait_price,
                        'price' => $service_type->price,
                        'fixed' => $service_type->fixed,
                        'tax' => $tax,
                        'min_wait_price' => $min_wait_price,
                        'wallet' => $trip->use_wallet,
                        'time_trip' => $time_trip,
                        'time_trip_price' => $tripTimePrice,
                        'before_discount_total' => $before_discount_total,
                        'total' =>  ($total_trip + $wallet_user) - ( ($total_trip + $wallet_user) * ($gift_percentage/100) ) ,
                    ]);
                    $requestPayments->gift=$gift_percentage;
                    $requestPayments->total_before_gift= $total_trip + $wallet_user;
                    $requestPayments->save();

                    //$total_before_gift= $total_trip + $wallet_user;
                    $requestPayments2 = UserRequestPayment::find($requestPayments->id);
                    $user_requests = UserRequests::findOrFail($requestPayments->request_id);
                    $provider = Provider::findOrFail($user_requests->provider->id);
                    $user = User::findOrFail($user_requests->user->id);
                    $user->update([
                        'wallet_balance' => $total_wallet
                    ]);
                    if ($provider->fleet != 0) {
                        $company = Fleet::findOrFail($provider->fleet);
                        $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                        $commission = ($total * $commissionWebSite) / 100;
                        $company_money = $total - $commission;

                        $company->update([
                            'wallet_balance' => $company_money
                        ]);
                        return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments2);
                        //return responseJson(200, trans('admin.createMessageSuccess'), "Aloo");
                    } else {
                        $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                        $commission = ($total * $commissionWebSite) / 100;
                        $provider_money = $total - $commission;

                        $provider->update([
                            'wallet_balance' => $provider_money
                        ]);
                        return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments2);
                        //return responseJson(200, trans('admin.createMessageSuccess'), "Aloo");
                    }
                }
                if ($user->wallet_balance < $total && $user->wallet_balance > 0) {
                    // فى حاله ان كان مستخدم برومو كود وان كان مستخدم المحفظه وان كانت الفلوس الى فى المحفظه اقل من فلوس الرحله
                    $total_trip = $total - $user->wallet_balance;
                    $total_wallet = $user->wallet_balance - $total;

                    $gift_percentage =(int)Setting::get('gift_percentage', 0);
                    $requestPayments = UserRequestPayment::create([
                        'request_id' => $request->Trip_id,
                        'payment_mode' => 'CASH',
                        'distance' => $dist,
                        'discount_wallet' => $user->wallet_balance,
                        'WaitingTime' => $WaitingTime,
                        'WaitingPrice' => $service_type->min_wait_price,
                        'price' => $service_type->price,
                        'fixed' => $service_type->fixed,
                        'tax' => $tax,
                        'min_wait_price' => $min_wait_price,
                        'wallet' => $trip->use_wallet,
                        'time_trip' => $time_trip,
                        'time_trip_price' => $tripTimePrice,
                        'before_discount_total' => $before_discount_total,
                        'total' => ($total_trip + $wallet_user) - ( ($total_trip + $wallet_user) * ($gift_percentage/100) ) ,
                    ]);
                    $requestPayments->gift=$gift_percentage;
                    $requestPayments->total_before_gift= $total_trip + $wallet_user;
                    $requestPayments->save();

                    //$total_before_gift= $total_trip + $wallet_user;
                    $requestPayments2 = UserRequestPayment::find($requestPayments->id);
                    $user_requests = UserRequests::findOrFail($requestPayments->request_id);
                    $provider = Provider::findOrFail($user_requests->provider->id);
                    $user = User::findOrFail($user_requests->user->id);
                    $user->update([
                        'wallet_balance' => 0
                    ]);

                    $revenue = Revenue::where('provider_id', $provider->id)
                        ->where('status', '=', 'active')
                        ->orderBy('created_at', 'DESC')->first();
                    if ($provider->fleet != 0) {
                        // فى حاله ان كان مستخدم برومو كود وان كان مستخدم المحفظه وان كانت الفلوس الى فى المحفظه اقل من فلوس الرحله وان مكنش ليه شركه
                        $company = Fleet::findOrFail($provider->fleet);

                        $companySubscription = CompanySubscription::where('fleet_id', $company->id)
                            ->where('status', '=', 'active')
                            ->orderBy('created_at', 'DESC')->first();

                        if ($companySubscription != null) {
                            $requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total_before_gift]);
                            return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments2);
                            //return responseJson(200, trans('admin.createMessageSuccess'), "Aloo");
                        } else {
                            $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                            $commission = ($requestPayments->total_before_gift * $commissionWebSite) / 100;
                            $provider_money = $requestPayments->total_before_gift - $commission;
                            $requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                            $wallet_balance = $company->wallet_balance - $commission;
                            $company->update(['wallet_balance' => $wallet_balance]);

                            return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments2);
                            //return responseJson(200, trans('admin.createMessageSuccess'), "Aloo");
                        }
                    } else {

                        if ($revenue != null) {
                            $requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total_before_gift]);
                            return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments2);
                            //return responseJson(200, trans('admin.createMessageSuccess'), "Aloo");
                        } else {
                            $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                            $commission = ($requestPayments->total_before_gift * $commissionWebSite) / 100;
                            $provider_money = $requestPayments->total_before_gift - $commission;
                            $requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                            $wallet_balance = $provider->wallet_balance - $commission;
                            $provider->update(['wallet_balance' => $wallet_balance]);

                            return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments2);
                            //return responseJson(200, trans('admin.createMessageSuccess'), "Aloo");
                        }
                    }
                }
            }


            if ($user->wallet_balance >= $total_dist) {
                // فى حاله ان  كان مستخدم المحفظه وان كانت الفلوس الى فى المحفظه اكتر من او بتساوى من فلوس الرحله
                $total_wallet = $user->wallet_balance - $total_dist;
                $total_trip = $total_wallet - $user->wallet_balance;

                if ($total_trip <= 0) {
                    $total_trip = 0;
                }

                $gift_percentage =(int)Setting::get('gift_percentage', 0);
                $requestPayments = UserRequestPayment::create([
                    'request_id' => $request->Trip_id,
                    'payment_mode' => 'CASH',
                    'distance' => $dist,
                    'discount_wallet' => $total_dist,
                    'WaitingTime' => $WaitingTime,
                    'WaitingPrice' => $service_type->min_wait_price,
                    'price' => $service_type->price,
                    'fixed' => $service_type->fixed,
                    'min_wait_price' => $min_wait_price,
                    'tax' => $tax,
                    'wallet' => $trip->use_wallet,
                    'time_trip' => $time_trip,
                    'time_trip_price' => $tripTimePrice,
                    'before_discount_total' => $before_discount_total,
                    'total' => ($total_trip + $wallet_user) - ( ($total_trip + $wallet_user) * ($gift_percentage/100) ) ,
                ]);
                $requestPayments->gift=$gift_percentage;
                $requestPayments->total_before_gift= $total_trip + $wallet_user;
                $requestPayments->save();

                //$total_before_gift= $total_trip + $wallet_user;
                $requestPayments2 = UserRequestPayment::find($requestPayments->id);
                $user_requests = UserRequests::findOrFail($requestPayments->request_id);
                $provider = Provider::findOrFail($user_requests->provider->id);
                $user = User::findOrFail($user_requests->user->id);
                $user->update([
                    'wallet_balance' => $total_wallet
                ]);
                if ($provider->fleet != 0) {
                    $company = Fleet::findOrFail($provider->fleet);
                    $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                    $commission = $total_dist / $commissionWebSite;
                    $company_money = $total_dist - $commission;

                    $company->update([
                        'wallet_balance' => $company_money
                    ]);
                    return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments2);
                    //return responseJson(200, trans('admin.createMessageSuccess'), "Aloo");
                } else {
                    $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                    $commission = ($total_dist * $commissionWebSite) / 100;
                    $provider_money = $total_dist - $commission;

                    $provider->update([
                        'wallet_balance' => $provider_money
                    ]);
                    return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments2);
                    //return responseJson(200, trans('admin.createMessageSuccess'), "Aloo");
                }


            } elseif ($user->wallet_balance < $total_dist && $user->wallet_balance > 0) {
                // فى حاله ان كان مستخدم برومو كود وان كان مستخدم المحفظه وان كانت الفلوس الى فى المحفظه اقل من فلوس الرحله

                $total_trip = $total_dist - $user->wallet_balance;

                $gift_percentage =(int)Setting::get('gift_percentage', 0);
                $requestPayments = UserRequestPayment::create([
                    'request_id' => $request->Trip_id,
                    'payment_mode' => 'CASH',
                    'distance' => $dist,
                    'discount_wallet' => $user->wallet_balance,
                    'WaitingTime' => $WaitingTime,
                    'WaitingPrice' => $service_type->min_wait_price,
                    'price' => $service_type->price,
                    'fixed' => $service_type->fixed,
                    'tax' => $tax,
                    'min_wait_price' => $min_wait_price,
                    'wallet' => $trip->use_wallet,
                    'time_trip' => $time_trip,
                    'time_trip_price' => $tripTimePrice,
                    'before_discount_total' => $before_discount_total,
                    'total' => ($total_trip + $wallet_user) - ( ($total_trip + $wallet_user) * ($gift_percentage/100) ) ,
                ]);
                $requestPayments->gift=$gift_percentage;
                $requestPayments->total_before_gift= $total_trip + $wallet_user;
                $requestPayments->save();

                //$total_before_gift= $total_trip + $wallet_user;
                $requestPayments2 = UserRequestPayment::find($requestPayments->id);
                $user_requests = UserRequests::findOrFail($requestPayments->request_id);
                $provider = Provider::findOrFail($user_requests->provider->id);
                $user = User::findOrFail($user_requests->user->id);
                $user->update([
                    'wallet_balance' => 0
                ]);

                $revenue = Revenue::where('provider_id', $provider->id)
                    ->where('status', '=', 'active')
                    ->orderBy('created_at', 'DESC')->first();
                if ($provider->fleet != 0) {
                    $company = Fleet::findOrFail($provider->fleet);

                    $companySubscription = CompanySubscription::where('fleet_id', $company->id)
                        ->where('status', '=', 'active')
                        ->orderBy('created_at', 'DESC')->first();

                    if ($companySubscription != null) {
                        $requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total_before_gift]);
                        return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments2);
                        //return responseJson(200, trans('admin.createMessageSuccess'), "Aloo");
                    } else {
                        $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                        $commission = ($requestPayments->total_before_gift * $commissionWebSite) / 100;
                        $provider_money = $requestPayments->total_before_gift - $commission;
                        $requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                        $wallet_balance = $company->wallet_balance - $commission;
                        $company->update(['wallet_balance' => $wallet_balance]);

                        return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments2 );
                        //return responseJson(200, trans('admin.createMessageSuccess'), "Aloo");
                    }
                } else {

                    if ($revenue != null) {
                        $requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total_before_gift]);
                        return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments2);
                        //return responseJson(200, trans('admin.createMessageSuccess'), "Aloo");
                    } else {
                        $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                        $commission = ($requestPayments->total_before_gift * $commissionWebSite) / 100;
                        $provider_money = $requestPayments->total_before_gift - $commission;
                        $requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                        $wallet_balance = $provider->wallet_balance - $commission;
                        $provider->update(['wallet_balance' => $wallet_balance]);

                        return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments2);
                        //return responseJson(200, trans('admin.createMessageSuccess'), "Aloo");
                    }
                }
            }
        }

        $total_di = $service_type->price * $dist;
        $tax_percentage = Setting::get('tax_percentage', 10);
        $tax = (($total_di + $min_wait_price + $service_type->fixed) * $tax_percentage) / 100;
        $total_dis = $total_di + $min_wait_price + $service_type->fixed + $tax;
        $total_dist = (float)number_format((float)$total_dis, 2, '.', '');
        $before_discount_total = $total_dist;

        $promoCodeUser = PromocodeUsage::where('user_id', $user->id)->where('status', '=', 'USED')->orderBy('created_at', 'desc')->first();
        $user->update([
            'total_price' => $user->total_price + $before_discount_total,
            'total_trips' => $user->total_trips + 1,
        ]);
        if ($promoCodeUser != null) {
            // فى حاله ان كان مستخدم برومو كود
            $promoCode = Promocode::findOrFail($promoCodeUser->promocode_id);
            $promoCodeUser->update(['status' => 'EXPIRED']);
            //$promoCode

            //$discount = $total_dist / $promoCode->discount;
            $discount= $total_dist * ($promoCode->discount/100);
            $total = $total_dist - $discount + $tripTimePrice;

            $gift_percentage =(int)Setting::get('gift_percentage', 0);
            $requestPayments = UserRequestPayment::create([
                'request_id' => $request->Trip_id,
                'promocode_id' => $promoCode->id,
                'payment_mode' => 'CASH',
                'time_trip' => $time_trip,
                'time_trip_price' => $tripTimePrice,
                'distance' => $dist,
                'discount' => $discount,
                'WaitingTime' => $WaitingTime,
                'WaitingPrice' => $service_type->min_wait_price,
                'tax' => $tax,
                'price' => $service_type->price,
                'fixed' => $service_type->fixed,
                'min_wait_price' => $min_wait_price,
                'wallet' => $trip->use_wallet,
                'surge' => (float)$request->paid, // ما تم دفعه
                'before_discount_total' => $before_discount_total,
                'total' => ($total + $wallet_user) - ( ($total + $wallet_user) * ($gift_percentage/100) ) ,
            ]);
            $requestPayments->gift=$gift_percentage;
            $requestPayments->total_before_gift= $total + $wallet_user;
            $requestPayments->save();

            //$total_before_gift= $total_trip + $wallet_user;
            $requestPayments2 = UserRequestPayment::find($requestPayments->id);
            $user_requests = UserRequests::findOrFail($requestPayments->request_id);
            $provider = Provider::findOrFail($user_requests->provider->id);


            $revenue = Revenue::where('provider_id', $provider->id)
                ->where('status', '=', 'active')
                ->orderBy('created_at', 'DESC')->first();

            if ($provider->fleet != 0) {

                $company = Fleet::findOrFail($provider->fleet);

                $companySubscription = CompanySubscription::where('fleet_id', $company->id)
                    ->where('status', '=', 'active')
                    ->orderBy('created_at', 'DESC')->first();
                if ($companySubscription != null) {
                    $requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total_before_gift]);
                    return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments2);
                    //return responseJson(200, trans('admin.createMessageSuccess'), "Aloo");
                } else {
                    $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                    $commission = ($requestPayments->total_before_gift * $commissionWebSite) / 100;
                    $provider_money = $requestPayments->total_before_gift - $commission;
                    $requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                    $wallet_balance = $company->wallet_balance - $commission;
                    $company->update(['wallet_balance' => $wallet_balance]);

                    return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments2);
                    //return responseJson(200, trans('admin.createMessageSuccess'), "Aloo");
                }
            } else {

                if ($revenue != null) {
                    $requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total_before_gift]);
                    return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments2);
                } else {
                    $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                    $commission = ($requestPayments->total_before_gift * $commissionWebSite) / 100;
                    $provider_money = $requestPayments->total_before_gift - $commission;
                    $requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                    $wallet_balance = $provider->wallet_balance - $commission;
                    $provider->update(['wallet_balance' => $wallet_balance]);

                    return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments2);
                    //return responseJson(200, trans('admin.createMessageSuccess'), "Aloo");
                }
            }
        } else {
            // فى حاله ان لم يكن يستخدم البرومو كود

            $gift_percentage =(int)Setting::get('gift_percentage', 0);
            $requestPayments = UserRequestPayment::create([
                'request_id' => $request->Trip_id,
                'payment_mode' => 'CASH',
                'time_trip' => $time_trip,
                'time_trip_price' => $tripTimePrice,
                'WaitingTime' => $WaitingTime,
                'WaitingPrice' => $service_type->min_wait_price,
                'price' => $service_type->price,
                'fixed' => $service_type->fixed,
                'tax' => $tax,
                'min_wait_price' => $min_wait_price,
                'distance' => $dist,
                'wallet' => $trip->use_wallet,
                'before_discount_total' => $before_discount_total,
                'total' => ($total_dist + $wallet_user +$tripTimePrice) - ( ($total_dist + $wallet_user +$tripTimePrice) * ($gift_percentage/100) ) ,
            ]);
            $requestPayments->gift=$gift_percentage;
            $requestPayments->total_before_gift=$total_dist + $wallet_user +$tripTimePrice;
            $requestPayments->save();
            //return $requestPayments;

            //$total_before_gift= $total_trip + $wallet_user;
            $requestPayments2 = UserRequestPayment::find($requestPayments->id);

            //return $requestPayments2;
            $user_requests = UserRequests::findOrFail($requestPayments->request_id);
            $provider = Provider::findOrFail($user_requests->provider->id);

            $revenue = Revenue::where('provider_id', $provider->id)
                ->where('status', '=', 'active')
                ->orderBy('created_at', 'DESC')->first();


            if ($provider->fleet != 0) {
                // فى حاله ان مكنش مستخدم برومو كود وليه شركه
                $company = Fleet::findOrFail($provider->fleet);

                $companySubscription = CompanySubscription::where('fleet_id', $company->id)
                    ->where('status', '=', 'active')
                    ->orderBy('created_at', 'DESC')->first();

                if ($companySubscription != null) {
                    $requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total_before_gift]);
                    return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments2);
                    //return responseJson(200, trans('admin.createMessageSuccess'), "Aloo");
                } else {
                    $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                    $commission = ($requestPayments->total_before_gift * $commissionWebSite) / 100;
                    $provider_money = $requestPayments->total_before_gift - $commission;
                    $requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                    $wallet_balance = $company->wallet_balance - $commission;
                    $company->update(['wallet_balance' => $wallet_balance]);

                    return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments2);
                    //return responseJson(200, trans('admin.createMessageSuccess'), "Aloo");
                }
            } else {

                if ($revenue != null) {
                    $requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total_before_gift]);
                    return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments2);
                    //return responseJson(200, trans('admin.createMessageSuccess'), "Aloo");
                } else {
                    $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                    $commission = ($requestPayments->total_before_gift * $commissionWebSite) / 100;
                    $provider_money = $requestPayments->total_before_gift - $commission;
                    $requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                    $wallet_balance = $provider->wallet_balance - $commission;
                    $provider->update(['wallet_balance' => $wallet_balance]);

                    return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments2);
                    //return responseJson(200, trans('admin.createMessageSuccess'), "Aloo");
                }
            }
        }


    }

   /*public function requestPayment(Request $request)
   {
       $validator = validator()->make($request->all(), [
           'Trip_id' => 'required|exists:user_requests,id',
       ]);

       if ($validator->fails()) {
           return responseJson(422, $validator->errors()->first(), $validator->errors());
       }

       $this->checkData();

       $user_requests = UserRequests::findOrFail($request->trip_id);
       $requestPayment = UserRequestPayment::where('request_id', $user_requests->id)->first();
       $provider = Provider::findOrFail($user_requests->provider->id);
       $revenue = Revenue::where('provider_id', $provider->id)
           ->where('status', '=', 'active')
           ->orderBy('created_at', 'DESC')->first();

       if ($provider->fleet != 0) {
           $company = Fleet::findOrFail($provider->fleet);
           $companySubscription = CompanySubscription::where('fleet_id', $company->id)
               ->where('status', '=', 'active')
               ->orderBy('created_at', 'DESC')->first();
           if ($companySubscription != null) {
               $requestPayment->update(['commision' => 0, 'provider_money' => $requestPayment->total]);
               return responseJson(200, 'تمت العمليه بنجاح', $requestPayment);
           } else {
               $commissionWebSite = (int)Setting::get('commission_percentage', 10);
               $commission = $requestPayment->total / $commissionWebSite;
               $provider_money = $requestPayment->total - $commission;
               $requestPayment->update(['commision' => $commission, 'provider_money' => $provider_money]);

               $wallet_balance = $company->wallet_balance - $commission;
               $company->update(['wallet_balance' => $wallet_balance]);

               return responseJson(200, 'تمت العمليه بنجاح', $requestPayment);
           }
       } else {
           if ($revenue != null) {
               $requestPayment->update(['commision' => 0, 'provider_money' => $requestPayment->total]);
               return responseJson(200, 'تمت العمليه بنجاح', $requestPayment);
           } else {
               $commissionWebSite = (int)Setting::get('commission_percentage', 10);
               $commission = $requestPayment->total / $commissionWebSite;
               $provider_money = $requestPayment->total - $commission;
               $requestPayment->update(['commision' => $commission, 'provider_money' => $provider_money]);

               $wallet_balance = $provider->wallet_balance - $commission;
               $provider->update(['wallet_balance' => $wallet_balance]);

               return responseJson(200, 'تمت العمليه بنجاح', $requestPayment);
           }
       }
   }*/

   public function calculatePayment(Request $request)
   {

    $validator = validator()->make($request->all(), [
        'Trip_id' => 'required|exists:user_requests,id',
    ]);

    if ($validator->fails()) {
        return responseJson(422, $validator->errors()->first(), $validator->errors());
    }

    $this->checkData();
    $trip = UserRequests::findOrFail((int)$request->Trip_id);
    $service_type = ServiceType::findOrFail($trip->service_type_id);
    $WaitingTime = $trip->started_at->diffInSeconds($trip->arrived_at) / 60 ;

    if($WaitingTime <= $service_type->waiting) {
        $WaitingTime = 0;
    }
    else {
        $WaitingTime = round($WaitingTime - $service_type->waiting);
    }

    $min_wait_price = $service_type->min_wait_price * ( $WaitingTime);


    $dist2 = $this->distance_between($trip->s_latitude, $trip->s_longitude, $trip->d_latitude, $trip->d_longitude, 'K');
    $dist = round((float)number_format((float)$dist2, 3, '.', ''));
    $user = User::findOrFail($trip->user_id);
    $wallet_user = 0;

    if ($user->wallet_balance < 0) {
        $wallet_user =  - $user->wallet_balance;

        // $user->update([
        //     'wallet_balance' => 0
        // ]);
    }

    $time_trips = $trip->finished_at->diffInSeconds($trip->started_at);
    $time_trip =round($time_trips / 60);
    $tripTimePrice=$service_type->minute *$time_trip;

    $tax_percentage = Setting::get('tax_percentage', 10);

    if ($trip->use_wallet == 1) {
        //return("Aloo");
        // فى حاله ان كان مستخدم المحفظه
        $total_di = $service_type->price * $dist;
        $tax_percentage = Setting::get('tax_percentage', 10);
        $tax = (($total_di + $min_wait_price + $service_type->fixed) * $tax_percentage) / 100;
        $total_dis = $total_di + $min_wait_price + $service_type->fixed + $tax + $tripTimePrice;
        $total_dist = (float)number_format((float)$total_dis, 2, '.', '');
        $before_discount_total = $total_dist  ;

        $promoCodeUser = PromocodeUsage::where('user_id', $user->id)->where('status', '=', 'USED')->orderBy('created_at', 'desc')->first();

        // $user->update([
        //     'total_price' => $user->total_price + $before_discount_total,
        //     'total_trips' => $user->total_trips + 1,
        // ]);

        if ($promoCodeUser != null) {
            // فى حاله ان كان مستخدم برومو كود والمحفظه
            $promoCode = Promocode::findOrFail($promoCodeUser->promocode_id);

            // $promoCodeUser->update([
            //     'status' => 'EXPIRED',
            // ]);

            //$discount = $total_dist / $promoCode->discount;
            $discount= $total_dist * ($promoCode->discount/100);
            $total = $total_dist - $discount;
            if ($user->wallet_balance >= $total) {
                // فى حاله ان كان مستخدم برومو كود وان كان مستخدم المحفظه وان كانت الفلوس الى فى المحفظه اكبر او بتساوى فلوس الرحله
                $total_wallet = $user->wallet_balance - $total;
                $total_trip = $total_wallet - $user->wallet_balance;
                if ($total_trip <= 0) {
                    $total_trip = 0;
                }



                ///////////////Fake Payments///////////////
                $gift_percentage =(int)Setting::get('gift_percentage', 0);
                $requestPayments =[
                    'request_id' => $request->Trip_id,
                    'payment_mode' => 'CASH',
                    'distance' => $dist,
                    'discount_wallet' => $total,
                    'WaitingTime' => $WaitingTime,
                    'WaitingPrice' => $service_type->min_wait_price,
                    'price' => $service_type->price,
                    'fixed' => $service_type->fixed,
                    'tax' => $tax,
                    'min_wait_price' => $min_wait_price,
                    'wallet' => $trip->use_wallet,
                    'time_trip' => $time_trip,
                    'time_trip_price' => $tripTimePrice,
                    'before_discount_total' => $before_discount_total,
                    'total' => ($total_trip + $wallet_user) - ( ($total_trip + $wallet_user) * ($gift_percentage/100) ) ,
                    'gift'=>$gift_percentage,
                    'total_before_gift'=>$total_trip + $wallet_user,
                ];
                //////////////////////////////////////////

                //$requestPayments2 = UserRequestPayment::find($requestPayments->id);
                //$user_requests = UserRequests::findOrFail($requestPayments->request_id);
                $user_requests = UserRequests::findOrFail((int)$request->Trip_id);
                $provider = Provider::findOrFail($user_requests->provider->id);
                $user = User::findOrFail($user_requests->user->id);
                // $user->update([
                //     'wallet_balance' => $total_wallet
                // ]);
                if ($provider->fleet != 0) {
                    $company = Fleet::findOrFail($provider->fleet);
                    $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                    $commission = ($total * $commissionWebSite) / 100;
                    $company_money = $total - $commission;

                    // $company->update([
                    //     'wallet_balance' => $company_money
                    // ]);

                    //$requestPaymentsX=json_encode($requestPayments);
                    //$x=$requestPayments[0]->toJson();

                    return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments);
                } else {
                    $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                    $commission = ($total * $commissionWebSite) / 100;
                    $provider_money = $total - $commission;

                    // $provider->update([
                    //     'wallet_balance' => $provider_money
                    // ]);

                    //$requestPaymentsX=json_encode($requestPayments);
                    return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments);
                }
            }
            if ($user->wallet_balance < $total && $user->wallet_balance > 0) {
                // فى حاله ان كان مستخدم برومو كود وان كان مستخدم المحفظه وان كانت الفلوس الى فى المحفظه اقل من فلوس الرحله
                $total_trip = $total - $user->wallet_balance;
                $total_wallet = $user->wallet_balance - $total;


                $gift_percentage =(int)Setting::get('gift_percentage', 0);
                $requestPayments=[
                    'request_id' => $request->Trip_id,
                    'payment_mode' => 'CASH',
                    'distance' => $dist,
                    'discount_wallet' => $user->wallet_balance,
                    'WaitingTime' => $WaitingTime,
                    'WaitingPrice' => $service_type->min_wait_price,
                    'price' => $service_type->price,
                    'fixed' => $service_type->fixed,
                    'tax' => $tax,
                    'min_wait_price' => $min_wait_price,
                    'wallet' => $trip->use_wallet,
                    'time_trip' => $time_trip,
                    'time_trip_price' => $tripTimePrice,
                    'before_discount_total' => $before_discount_total,
                    'total' => ($total_trip + $wallet_user) - ( ($total_trip + $wallet_user) * ($gift_percentage/100) ) ,
                    'gift'=>(int)Setting::get('gift_percentage', 10),
                    'total_before_gift'=>$total_trip + $wallet_user,
                ];

                //$requestPayments2 = UserRequestPayment::find($requestPayments->id);

                //$user_requests = UserRequests::findOrFail($requestPayments->request_id);
                $user_requests = UserRequests::findOrFail((int)$request->Trip_id);
                $provider = Provider::findOrFail($user_requests->provider->id);
                $user = User::findOrFail($user_requests->user->id);
                // $user->update([
                //     'wallet_balance' => 0
                // ]);

                $revenue = Revenue::where('provider_id', $provider->id)
                    ->where('status', '=', 'active')
                    ->orderBy('created_at', 'DESC')->first();
                if ($provider->fleet != 0) {
                    // فى حاله ان كان مستخدم برومو كود وان كان مستخدم المحفظه وان كانت الفلوس الى فى المحفظه اقل من فلوس الرحله وان مكنش ليه شركه
                    $company = Fleet::findOrFail($provider->fleet);

                    $companySubscription = CompanySubscription::where('fleet_id', $company->id)
                        ->where('status', '=', 'active')
                        ->orderBy('created_at', 'DESC')->first();

                    if ($companySubscription != null) {
                        //$requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total]);

                        //$requestPaymentsX=json_encode($requestPayments);
                        return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments);
                    } else {
                        $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                        $commission = ($requestPayments["total"] * $commissionWebSite) / 100;
                        $provider_money = $requestPayments["total"] - $commission;
                        //$requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                        $wallet_balance = $company->wallet_balance - $commission;
                        //$company->update(['wallet_balance' => $wallet_balance]);

                        //$requestPaymentsX=json_encode($requestPayments);
                        return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments);
                    }
                } else {

                    if ($revenue != null) {
                        //$requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total]);

                        //$requestPaymentsX=json_encode($requestPayments);
                        return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments);
                    } else {
                        $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                        $commission = ($requestPayments["total"] * $commissionWebSite) / 100;
                        $provider_money = $requestPayments["total"] - $commission;
                        //$requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                        $wallet_balance = $provider->wallet_balance - $commission;
                        //$provider->update(['wallet_balance' => $wallet_balance]);

                        //$requestPaymentsX=json_encode($requestPayments);
                        return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments);
                    }
                }
            }
        }


        if ($user->wallet_balance >= $total_dist) {
            // فى حاله ان  كان مستخدم المحفظه وان كانت الفلوس الى فى المحفظه اكتر من او بتساوى من فلوس الرحله
            $total_wallet = $user->wallet_balance - $total_dist;
            $total_trip = $total_wallet - $user->wallet_balance;

            if ($total_trip <= 0) {
                $total_trip = 0;
            }


            $gift_percentage =(int)Setting::get('gift_percentage', 0);
            $requestPayments=[
                'request_id' => $request->Trip_id,
                'payment_mode' => 'CASH',
                'distance' => $dist,
                'discount_wallet' => $total_dist,
                'WaitingTime' => $WaitingTime,
                'WaitingPrice' => $service_type->min_wait_price,
                'price' => $service_type->price,
                'fixed' => $service_type->fixed,
                'min_wait_price' => $min_wait_price,
                'tax' => $tax,
                'wallet' => $trip->use_wallet,
                'time_trip' => $time_trip,
                'time_trip_price' => $tripTimePrice,
                'before_discount_total' => $before_discount_total,
                'total' => ($total_trip + $wallet_user) - ( ($total_trip + $wallet_user) * ($gift_percentage/100) ) ,
                'gift'=>$gift_percentage,
                'total_before_gift'=>$total_trip + $wallet_user,
            ];


            //$requestPayments2 = UserRequestPayment::find(1725);

            //$user_requests = UserRequests::findOrFail($requestPayments->request_id);
            $user_requests = UserRequests::findOrFail((int)$request->Trip_id);
            $provider = Provider::findOrFail($user_requests->provider->id);
            $user = User::findOrFail($user_requests->user->id);
            // $user->update([
            //     'wallet_balance' => $total_wallet
            // ]);
            if ($provider->fleet != 0) {
                $company = Fleet::findOrFail($provider->fleet);
                $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                $commission = $total_dist / $commissionWebSite;
                $company_money = $total_dist - $commission;

                // $company->update([
                //     'wallet_balance' => $company_money
                // ]);
                return responseJson(200, trans('admin.createMessageSuccess'),$requestPayments);
            } else {
                $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                $commission = ($total_dist * $commissionWebSite) / 100;
                $provider_money = $total_dist - $commission;

                // $provider->update([
                //     'wallet_balance' => $provider_money
                // ]);
                return responseJson(200, trans('admin.createMessageSuccess'),  $requestPayments);
            }


        } elseif ($user->wallet_balance < $total_dist && $user->wallet_balance > 0) {
            // فى حاله ان كان مستخدم برومو كود وان كان مستخدم المحفظه وان كانت الفلوس الى فى المحفظه اقل من فلوس الرحله

            $total_trip = $total_dist - $user->wallet_balance;


            $gift_percentage =(int)Setting::get('gift_percentage', 0);
            $requestPayments =[
                'request_id' => $request->Trip_id,
                'payment_mode' => 'CASH',
                'distance' => $dist,
                'discount_wallet' => $user->wallet_balance,
                'WaitingTime' => $WaitingTime,
                'WaitingPrice' => $service_type->min_wait_price,
                'price' => $service_type->price,
                'fixed' => $service_type->fixed,
                'tax' => $tax,
                'min_wait_price' => $min_wait_price,
                'wallet' => $trip->use_wallet,
                'time_trip' => $time_trip,
                'time_trip_price' => $tripTimePrice,
                'before_discount_total' => $before_discount_total,
                'total' => ($total_trip + $wallet_user) - ( ($total_trip + $wallet_user) * ($gift_percentage/100) ) ,
                'gift'=>$gift_percentage,
                'total_before_gift'=>$total_trip + $wallet_user,
            ];

            //$requestPayments2 = UserRequestPayment::find($requestPayments->id);

            //$user_requests = UserRequests::findOrFail($requestPayments->request_id);
            $user_requests = UserRequests::findOrFail((int)$request->Trip_id);
            $provider = Provider::findOrFail($user_requests->provider->id);
            $user = User::findOrFail($user_requests->user->id);
            // $user->update([
            //     'wallet_balance' => 0
            // ]);

            $revenue = Revenue::where('provider_id', $provider->id)
                ->where('status', '=', 'active')
                ->orderBy('created_at', 'DESC')->first();
            if ($provider->fleet != 0) {
                $company = Fleet::findOrFail($provider->fleet);

                $companySubscription = CompanySubscription::where('fleet_id', $company->id)
                    ->where('status', '=', 'active')
                    ->orderBy('created_at', 'DESC')->first();

                if ($companySubscription != null) {
                    //$requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total]);
                    return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments);
                } else {
                    $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                    $commission = ($requestPayments['total'] * $commissionWebSite) / 100;
                    $provider_money = $requestPayments['total'] - $commission;
                    //$requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                    $wallet_balance = $company->wallet_balance - $commission;
                    //$company->update(['wallet_balance' => $wallet_balance]);

                    return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments);
                }
            } else {

                if ($revenue != null) {
                    //$requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total]);
                    return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments);
                } else {
                    $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                    $commission = ($requestPayments['total'] * $commissionWebSite) / 100;
                    $provider_money = $requestPayments['total'] - $commission;
                    //$requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                    $wallet_balance = $provider->wallet_balance - $commission;
                    //$provider->update(['wallet_balance' => $wallet_balance]);

                    return responseJson(200, trans('admin.createMessageSuccess'),$requestPayments);
                }
            }
        }
    }

    $total_di = $service_type->price * $dist;
    $tax_percentage = Setting::get('tax_percentage', 10);
    $tax = (($total_di + $min_wait_price + $service_type->fixed) * $tax_percentage) / 100;
    $total_dis = $total_di + $min_wait_price + $service_type->fixed + $tax;
    $total_dist = (float)number_format((float)$total_dis, 2, '.', '');
    $before_discount_total = $total_dist;

    $promoCodeUser = PromocodeUsage::where('user_id', $user->id)->where('status', '=', 'USED')->orderBy('created_at', 'desc')->first();
    // $user->update([
    //     'total_price' => $user->total_price + $before_discount_total,
    //     'total_trips' => $user->total_trips + 1,
    // ]);
    if ($promoCodeUser != null) {
        // فى حاله ان كان مستخدم برومو كود
        $promoCode = Promocode::findOrFail($promoCodeUser->promocode_id);
        //$promoCodeUser->update(['status' => 'EXPIRED']);

        //$discount = $total_dist / $promoCode->discount;
        $discount= $total_dist * ($promoCode->discount/100);
        $total = $total_dist - $discount + $tripTimePrice;

        $gift_percentage =(int)Setting::get('gift_percentage', 0);
        $requestPayments=[
            'request_id' => $request->Trip_id,
            'promocode_id' => $promoCode->id,
            'payment_mode' => 'CASH',
            'time_trip' => $time_trip,
            'time_trip_price' => $tripTimePrice,
            'distance' => $dist,
            'discount' => $discount,
            'WaitingTime' => $WaitingTime,
            'WaitingPrice' => $service_type->min_wait_price,
            'tax' => $tax,
            'price' => $service_type->price,
            'fixed' => $service_type->fixed,
            'min_wait_price' => $min_wait_price,
            'wallet' => $trip->use_wallet,
            'surge' => (float)$request->paid, // ما تم دفعه
            'before_discount_total' => $before_discount_total,
            'total' => ($total + $wallet_user) - ( ($total + $wallet_user) * ($gift_percentage/100) ) ,
            'gift'=>$gift_percentage,
            'total_before_gift'=> $total + $wallet_user ,
        ];

        //$requestPayments2 = UserRequestPayment::find($requestPayments->id);
        //$user_requests = UserRequests::findOrFail($requestPayments->request_id);
        $user_requests = UserRequests::findOrFail((int)$request->Trip_id);
        $provider = Provider::findOrFail($user_requests->provider->id);


        $revenue = Revenue::where('provider_id', $provider->id)
            ->where('status', '=', 'active')
            ->orderBy('created_at', 'DESC')->first();

        if ($provider->fleet != 0) {

            $company = Fleet::findOrFail($provider->fleet);

            $companySubscription = CompanySubscription::where('fleet_id', $company->id)
                ->where('status', '=', 'active')
                ->orderBy('created_at', 'DESC')->first();
            if ($companySubscription != null) {
                //$requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total]);
                return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments);
            } else {
                $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                $commission = ($requestPayments['total'] * $commissionWebSite) / 100;
                $provider_money = $requestPayments['total'] - $commission;
                //$requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                $wallet_balance = $company->wallet_balance - $commission;
                //$company->update(['wallet_balance' => $wallet_balance]);

                return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments);
            }
        } else {

            if ($revenue != null) {
                //$requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total]);
                return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments);
            } else {
                $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                $commission = ($requestPayments['total'] * $commissionWebSite) / 100;
                $provider_money = $requestPayments['total'] - $commission;
                //$requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                $wallet_balance = $provider->wallet_balance - $commission;
                //$provider->update(['wallet_balance' => $wallet_balance]);

                return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments);
            }
        }
    } else {
        // فى حاله ان لم يكن يستخدم البرومو كود

        $gift_percentage =(int)Setting::get('gift_percentage', 0);
        $requestPayments=[
            'request_id' => $request->Trip_id,
            'payment_mode' => 'CASH',
            'time_trip' => $time_trip,
            'time_trip_price' => $tripTimePrice,
            'WaitingTime' => $WaitingTime,
            'WaitingPrice' => $service_type->min_wait_price,
            'price' => $service_type->price,
            'fixed' => $service_type->fixed,
            'tax' => $tax,
            'min_wait_price' => $min_wait_price,
            'distance' => $dist,
            'wallet' => $trip->use_wallet,
            'before_discount_total' => $before_discount_total,
            'total' => ($total_dist + $wallet_user +$tripTimePrice) - ( ($total_dist + $wallet_user +$tripTimePrice) * ($gift_percentage/100) ) ,
            'gift'=>$gift_percentage,
            'total_before_gift'=> $total_dist + $wallet_user +$tripTimePrice,
        ];

        //$requestPayments2 = UserRequestPayment::find($requestPayments->id);
        //$user_requests = UserRequests::findOrFail($requestPayments->request_id);
        $user_requests = UserRequests::findOrFail((int)$request->Trip_id);
        $provider = Provider::findOrFail($user_requests->provider->id);

        $revenue = Revenue::where('provider_id', $provider->id)
            ->where('status', '=', 'active')
            ->orderBy('created_at', 'DESC')->first();


        if ($provider->fleet != 0) {
            // فى حاله ان مكنش مستخدم برومو كود وليه شركه
            $company = Fleet::findOrFail($provider->fleet);

            $companySubscription = CompanySubscription::where('fleet_id', $company->id)
                ->where('status', '=', 'active')
                ->orderBy('created_at', 'DESC')->first();

            if ($companySubscription != null) {
                //$requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total]);
                return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments);
            } else {
                $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                $commission = ($requestPayments['total'] * $commissionWebSite) / 100;
                $provider_money = $requestPayments['total'] - $commission;
                //$requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                $wallet_balance = $company->wallet_balance - $commission;
                //$company->update(['wallet_balance' => $wallet_balance]);

                return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments);
            }
        } else {

            if ($revenue != null) {
                //$requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total]);
                //return "Alo";
                return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments);
            } else {
                $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                $commission = ($requestPayments['total'] * $commissionWebSite) / 100;
                $provider_money = $requestPayments['total'] - $commission;
                //$requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                $wallet_balance = $provider->wallet_balance - $commission;
                //$provider->update(['wallet_balance' => $wallet_balance]);

                return responseJson(200, trans('admin.createMessageSuccess'), $requestPayments);
            }
        }
    }

   }

   public function droppedBill(Request $request)
   {
    $validator = validator()->make($request->all(), [
        'Trip_id' => 'required|exists:user_requests,id',
    ]);

    if ($validator->fails()) {
        return responseJson(422, $validator->errors()->first(), $validator->errors());
    }

    $this->checkData();
    $trip = UserRequests::findOrFail((int)$request->Trip_id);
    $service_type = ServiceType::findOrFail($trip->service_type_id);
    $WaitingTime = $trip->started_at->diffInSeconds($trip->arrived_at) / 60 ;

    if($WaitingTime <= $service_type->waiting) {
        $WaitingTime = 0;
    }
    else {
        $WaitingTime = round($WaitingTime - $service_type->waiting);
    }

    $min_wait_price = $service_type->min_wait_price * ( $WaitingTime);


    $dist2 = $this->distance_between($trip->s_latitude, $trip->s_longitude, $trip->d_latitude, $trip->d_longitude, 'K');
    $dist = round((float)number_format((float)$dist2, 3, '.', ''));
    $user = User::findOrFail($trip->user_id);
    $wallet_user = 0;

    if ($user->wallet_balance < 0) {
        $wallet_user =  - $user->wallet_balance;

        // $user->update([
        //     'wallet_balance' => 0
        // ]);
    }

    $time_trips = $trip->finished_at->diffInSeconds($trip->started_at);
    $time_trip =round($time_trips / 60);
    $tripTimePrice=$service_type->minute *$time_trip;

    $tax_percentage = Setting::get('tax_percentage', 10);

    if ($trip->use_wallet == 1) {
        //return("Aloo");
        // فى حاله ان كان مستخدم المحفظه
        $total_di = $service_type->price * $dist;
        $tax_percentage = Setting::get('tax_percentage', 10);
        $tax = (($total_di + $min_wait_price + $service_type->fixed) * $tax_percentage) / 100;
        $total_dis = $total_di + $min_wait_price + $service_type->fixed + $tax + $tripTimePrice;
        $total_dist = (float)number_format((float)$total_dis, 2, '.', '');
        $before_discount_total = $total_dist  ;

        $promoCodeUser = PromocodeUsage::where('user_id', $user->id)->where('status', '=', 'USED')->orderBy('created_at', 'desc')->first();

        // $user->update([
        //     'total_price' => $user->total_price + $before_discount_total,
        //     'total_trips' => $user->total_trips + 1,
        // ]);

        if ($promoCodeUser != null) {
            // فى حاله ان كان مستخدم برومو كود والمحفظه
            $promoCode = Promocode::findOrFail($promoCodeUser->promocode_id);

            // $promoCodeUser->update([
            //     'status' => 'EXPIRED',
            // ]);

            //$discount = $total_dist / $promoCode->discount;
            $discount= $total_dist * ($promoCode->discount/100);
            $total = $total_dist - $discount;
            if ($user->wallet_balance >= $total) {
                // فى حاله ان كان مستخدم برومو كود وان كان مستخدم المحفظه وان كانت الفلوس الى فى المحفظه اكبر او بتساوى فلوس الرحله
                $total_wallet = $user->wallet_balance - $total;
                $total_trip = $total_wallet - $user->wallet_balance;
                if ($total_trip <= 0) {
                    $total_trip = 0;
                }



                ///////////////Fake Payments///////////////
                $gift_percentage =(int)Setting::get('gift_percentage', 0);
                $requestPayments =[
                    'request_id' => $request->Trip_id,
                    'payment_mode' => 'CASH',
                    'distance' => $dist,
                    'discount_wallet' => $total,
                    'WaitingTime' => $WaitingTime,
                    'WaitingPrice' => $service_type->min_wait_price,
                    'price' => $service_type->price,
                    'fixed' => $service_type->fixed,
                    'tax' => $tax,
                    'min_wait_price' => $min_wait_price,
                    'wallet' => $trip->use_wallet,
                    'time_trip' => $time_trip,
                    'time_trip_price' => $tripTimePrice,
                    'before_discount_total' => $before_discount_total,
                    'total' => ($total_trip + $wallet_user) - ( ($total_trip + $wallet_user) * ($gift_percentage/100) ) ,
                    'gift'=>$gift_percentage,
                    'total_before_gift'=>$total_trip + $wallet_user,
                ];

                $bill=[
                    'fixed_price' => round($requestPayments['fixed'] * 10) / 10,//????? ??????
                    'distance' => round($requestPayments['distance'] * 10) / 10,//???????
                    'distance_price' => round($requestPayments['price'] * $requestPayments['distance'] * 10) / 10,//????? ???????
                    'wattingTime' => round($requestPayments['WaitingTime']),//??? ????????
                    'time_price' => round($requestPayments['min_wait_price'] * 10) / 10,//????? ????????
                    'tripTime' => round($requestPayments['time_trip']),//??? ??????
                    'watting_price' => round($requestPayments['time_trip_price'] *10) / 10,//????? ??? ??????
                    'gift' => $gift_percentage,
                    'total_before_gift' => round($requestPayments['total_before_gift'] * 10) / 10,//???????

                    'tax' => round($requestPayments['tax'] * 10) / 10,//???????
                    'total_price' => round($requestPayments['total'] * 10) / 10,//????????

                // watting_price: Math.round(response.body.data.WaitingPrice * 10) / 10,

                    // 'discount_wallet' => round($requestPayments['discount_wallet'] * 10) / 10,//????? ?? ????
                    'Trip_id' => $request->Trip_id
                ];
                //////////////////////////////////////////

                //$requestPayments2 = UserRequestPayment::find($requestPayments->id);
                //$user_requests = UserRequests::findOrFail($requestPayments->request_id);
                $user_requests = UserRequests::findOrFail((int)$request->Trip_id);
                $provider = Provider::findOrFail($user_requests->provider->id);
                $user = User::findOrFail($user_requests->user->id);
                // $user->update([
                //     'wallet_balance' => $total_wallet
                // ]);
                if ($provider->fleet != 0) {
                    $company = Fleet::findOrFail($provider->fleet);
                    $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                    $commission = ($total * $commissionWebSite) / 100;
                    $company_money = $total - $commission;

                    // $company->update([
                    //     'wallet_balance' => $company_money
                    // ]);

                    //$requestPaymentsX=json_encode($requestPayments);
                    //$x=$requestPayments[0]->toJson();

                    return responseJson(200, trans('admin.createMessageSuccess'), $bill);
                } else {
                    $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                    $commission = ($total * $commissionWebSite) / 100;
                    $provider_money = $total - $commission;

                    // $provider->update([
                    //     'wallet_balance' => $provider_money
                    // ]);

                    //$requestPaymentsX=json_encode($requestPayments);
                    return responseJson(200, trans('admin.createMessageSuccess'), $bill);
                }
            }
            if ($user->wallet_balance < $total && $user->wallet_balance > 0) {
                // فى حاله ان كان مستخدم برومو كود وان كان مستخدم المحفظه وان كانت الفلوس الى فى المحفظه اقل من فلوس الرحله
                $total_trip = $total - $user->wallet_balance;
                $total_wallet = $user->wallet_balance - $total;



                $gift_percentage =(int)Setting::get('gift_percentage', 0);
                $requestPayments=[
                    'request_id' => $request->Trip_id,
                    'payment_mode' => 'CASH',
                    'distance' => $dist,
                    'discount_wallet' => $user->wallet_balance,
                    'WaitingTime' => $WaitingTime,
                    'WaitingPrice' => $service_type->min_wait_price,
                    'price' => $service_type->price,
                    'fixed' => $service_type->fixed,
                    'tax' => $tax,
                    'min_wait_price' => $min_wait_price,
                    'wallet' => $trip->use_wallet,
                    'time_trip' => $time_trip,
                    'time_trip_price' => $tripTimePrice,
                    'before_discount_total' => $before_discount_total,
                    'total' => ($total_trip + $wallet_user) - ( ($total_trip + $wallet_user) * ($gift_percentage/100) ) ,
                    'gift'=>(int)Setting::get('gift_percentage', 10),
                    'total_before_gift'=>$total_trip + $wallet_user,
                ];

                $bill=[
                    'fixed_price' => round($requestPayments['fixed'] * 10) / 10,//????? ??????
                    'distance' => round($requestPayments['distance'] * 10) / 10,//???????
                    'distance_price' => round($requestPayments['price'] * $requestPayments['distance'] * 10) / 10,//????? ???????
                    'wattingTime' => round($requestPayments['WaitingTime']),//??? ????????
                    'time_price' => round($requestPayments['min_wait_price'] * 10) / 10,//????? ????????
                    'tripTime' => round($requestPayments['time_trip']),//??? ??????
                    'watting_price' => round($requestPayments['time_trip_price'] *10) / 10,//????? ??? ??????
                    'gift' => $gift_percentage,
                    'total_before_gift' => round($requestPayments['total_before_gift'] * 10) / 10,//???????

                    'tax' => round($requestPayments['tax'] * 10) / 10,//???????
                    'total_price' => round($requestPayments['total'] * 10) / 10,//????????

                // watting_price: Math.round(response.body.data.WaitingPrice * 10) / 10,

                    // 'discount_wallet' => round($requestPayments['discount_wallet'] * 10) / 10,//????? ?? ????
                    'Trip_id' => $request->Trip_id
                ];

                //$requestPayments2 = UserRequestPayment::find($requestPayments->id);

                //$user_requests = UserRequests::findOrFail($requestPayments->request_id);
                $user_requests = UserRequests::findOrFail((int)$request->Trip_id);
                $provider = Provider::findOrFail($user_requests->provider->id);
                $user = User::findOrFail($user_requests->user->id);
                // $user->update([
                //     'wallet_balance' => 0
                // ]);

                $revenue = Revenue::where('provider_id', $provider->id)
                    ->where('status', '=', 'active')
                    ->orderBy('created_at', 'DESC')->first();
                if ($provider->fleet != 0) {
                    // فى حاله ان كان مستخدم برومو كود وان كان مستخدم المحفظه وان كانت الفلوس الى فى المحفظه اقل من فلوس الرحله وان مكنش ليه شركه
                    $company = Fleet::findOrFail($provider->fleet);

                    $companySubscription = CompanySubscription::where('fleet_id', $company->id)
                        ->where('status', '=', 'active')
                        ->orderBy('created_at', 'DESC')->first();

                    if ($companySubscription != null) {
                        //$requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total]);

                        //$requestPaymentsX=json_encode($requestPayments);
                        return responseJson(200, trans('admin.createMessageSuccess'), $bill);
                    } else {
                        $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                        $commission = ($requestPayments["total"] * $commissionWebSite) / 100;
                        $provider_money = $requestPayments["total"] - $commission;
                        //$requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                        $wallet_balance = $company->wallet_balance - $commission;
                        //$company->update(['wallet_balance' => $wallet_balance]);

                        //$requestPaymentsX=json_encode($requestPayments);
                        return responseJson(200, trans('admin.createMessageSuccess'), $bill);
                    }
                } else {

                    if ($revenue != null) {
                        //$requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total]);

                        //$requestPaymentsX=json_encode($requestPayments);
                        return responseJson(200, trans('admin.createMessageSuccess'), $bill);
                    } else {
                        $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                        $commission = ($requestPayments["total"] * $commissionWebSite) / 100;
                        $provider_money = $requestPayments["total"] - $commission;
                        //$requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                        $wallet_balance = $provider->wallet_balance - $commission;
                        //$provider->update(['wallet_balance' => $wallet_balance]);

                        //$requestPaymentsX=json_encode($requestPayments);
                        return responseJson(200, trans('admin.createMessageSuccess'), $bill);
                    }
                }
            }
        }


        if ($user->wallet_balance >= $total_dist) {
            // فى حاله ان  كان مستخدم المحفظه وان كانت الفلوس الى فى المحفظه اكتر من او بتساوى من فلوس الرحله
            $total_wallet = $user->wallet_balance - $total_dist;
            $total_trip = $total_wallet - $user->wallet_balance;

            if ($total_trip <= 0) {
                $total_trip = 0;
            }



            $gift_percentage =(int)Setting::get('gift_percentage', 0);
            $requestPayments=[
                'request_id' => $request->Trip_id,
                'payment_mode' => 'CASH',
                'distance' => $dist,
                'discount_wallet' => $total_dist,
                'WaitingTime' => $WaitingTime,
                'WaitingPrice' => $service_type->min_wait_price,
                'price' => $service_type->price,
                'fixed' => $service_type->fixed,
                'min_wait_price' => $min_wait_price,
                'tax' => $tax,
                'wallet' => $trip->use_wallet,
                'time_trip' => $time_trip,
                'time_trip_price' => $tripTimePrice,
                'before_discount_total' => $before_discount_total,
                'total' => ($total_trip + $wallet_user) - ( ($total_trip + $wallet_user) * ($gift_percentage/100) ) ,
                'gift'=>$gift_percentage,
                'total_before_gift'=>$total_trip + $wallet_user,
            ];

            $bill=[
                'fixed_price' => round($requestPayments['fixed'] * 10) / 10,//????? ??????
                'distance' => round($requestPayments['distance'] * 10) / 10,//???????
                'distance_price' => round($requestPayments['price'] * $requestPayments['distance'] * 10) / 10,//????? ???????
                'wattingTime' => round($requestPayments['WaitingTime']),//??? ????????
                'time_price' => round($requestPayments['min_wait_price'] * 10) / 10,//????? ????????
                'tripTime' => round($requestPayments['time_trip']),//??? ??????
                'watting_price' => round($requestPayments['time_trip_price'] *10) / 10,//????? ??? ??????
                'gift' => $gift_percentage,
                'total_before_gift' => round($requestPayments['total_before_gift'] * 10) / 10,//???????

                'tax' => round($requestPayments['tax'] * 10) / 10,//???????
                'total_price' => round($requestPayments['total'] * 10) / 10,//????????

            // watting_price: Math.round(response.body.data.WaitingPrice * 10) / 10,

                // 'discount_wallet' => round($requestPayments['discount_wallet'] * 10) / 10,//????? ?? ????
                'Trip_id' => $request->Trip_id
            ];

            //$requestPayments2 = UserRequestPayment::find(1725);

            //$user_requests = UserRequests::findOrFail($requestPayments->request_id);
            $user_requests = UserRequests::findOrFail((int)$request->Trip_id);
            $provider = Provider::findOrFail($user_requests->provider->id);
            $user = User::findOrFail($user_requests->user->id);
            // $user->update([
            //     'wallet_balance' => $total_wallet
            // ]);
            if ($provider->fleet != 0) {
                $company = Fleet::findOrFail($provider->fleet);
                $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                $commission = $total_dist / $commissionWebSite;
                $company_money = $total_dist - $commission;

                // $company->update([
                //     'wallet_balance' => $company_money
                // ]);
                return responseJson(200, trans('admin.createMessageSuccess'),$bill);
            } else {
                $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                $commission = ($total_dist * $commissionWebSite) / 100;
                $provider_money = $total_dist - $commission;

                // $provider->update([
                //     'wallet_balance' => $provider_money
                // ]);
                return responseJson(200, trans('admin.createMessageSuccess'),  $bill);
            }


        } elseif ($user->wallet_balance < $total_dist && $user->wallet_balance > 0) {
            // فى حاله ان كان مستخدم برومو كود وان كان مستخدم المحفظه وان كانت الفلوس الى فى المحفظه اقل من فلوس الرحله

            $total_trip = $total_dist - $user->wallet_balance;



            $gift_percentage =(int)Setting::get('gift_percentage', 0);
            $requestPayments =[
                'request_id' => $request->Trip_id,
                'payment_mode' => 'CASH',
                'distance' => $dist,
                'discount_wallet' => $user->wallet_balance,
                'WaitingTime' => $WaitingTime,
                'WaitingPrice' => $service_type->min_wait_price,
                'price' => $service_type->price,
                'fixed' => $service_type->fixed,
                'tax' => $tax,
                'min_wait_price' => $min_wait_price,
                'wallet' => $trip->use_wallet,
                'time_trip' => $time_trip,
                'time_trip_price' => $tripTimePrice,
                'before_discount_total' => $before_discount_total,
                'total' => ($total_trip + $wallet_user) - ( ($total_trip + $wallet_user) * ($gift_percentage/100) ) ,
                'gift'=>$gift_percentage,
                'total_before_gift'=>$total_trip + $wallet_user,
            ];

            $bill=[
                'fixed_price' => round($requestPayments['fixed'] * 10) / 10,//????? ??????
                'distance' => round($requestPayments['distance'] * 10) / 10,//???????
                'distance_price' => round($requestPayments['price'] * $requestPayments['distance'] * 10) / 10,//????? ???????
                'wattingTime' => round($requestPayments['WaitingTime']),//??? ????????
                'time_price' => round($requestPayments['min_wait_price'] * 10) / 10,//????? ????????
                'tripTime' => round($requestPayments['time_trip']),//??? ??????
                'watting_price' => round($requestPayments['time_trip_price'] *10) / 10,//????? ??? ??????
                'gift' => $gift_percentage,
                'total_before_gift' => round($requestPayments['total_before_gift'] * 10) / 10,//???????

                'tax' => round($requestPayments['tax'] * 10) / 10,//???????
                'total_price' => round($requestPayments['total'] * 10) / 10,//????????

            // watting_price: Math.round(response.body.data.WaitingPrice * 10) / 10,

                // 'discount_wallet' => round($requestPayments['discount_wallet'] * 10) / 10,//????? ?? ????
                'Trip_id' => $request->Trip_id
            ];

            //$requestPayments2 = UserRequestPayment::find($requestPayments->id);

            //$user_requests = UserRequests::findOrFail($requestPayments->request_id);
            $user_requests = UserRequests::findOrFail((int)$request->Trip_id);
            $provider = Provider::findOrFail($user_requests->provider->id);
            $user = User::findOrFail($user_requests->user->id);
            // $user->update([
            //     'wallet_balance' => 0
            // ]);

            $revenue = Revenue::where('provider_id', $provider->id)
                ->where('status', '=', 'active')
                ->orderBy('created_at', 'DESC')->first();
            if ($provider->fleet != 0) {
                $company = Fleet::findOrFail($provider->fleet);

                $companySubscription = CompanySubscription::where('fleet_id', $company->id)
                    ->where('status', '=', 'active')
                    ->orderBy('created_at', 'DESC')->first();

                if ($companySubscription != null) {
                    //$requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total]);
                    return responseJson(200, trans('admin.createMessageSuccess'), $bill);
                } else {
                    $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                    $commission = ($requestPayments['total'] * $commissionWebSite) / 100;
                    $provider_money = $requestPayments['total'] - $commission;
                    //$requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                    $wallet_balance = $company->wallet_balance - $commission;
                    //$company->update(['wallet_balance' => $wallet_balance]);

                    return responseJson(200, trans('admin.createMessageSuccess'), $bill);
                }
            } else {

                if ($revenue != null) {
                    //$requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total]);
                    return responseJson(200, trans('admin.createMessageSuccess'), $bill);
                } else {
                    $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                    $commission = ($requestPayments['total'] * $commissionWebSite) / 100;
                    $provider_money = $requestPayments['total'] - $commission;
                    //$requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                    $wallet_balance = $provider->wallet_balance - $commission;
                    //$provider->update(['wallet_balance' => $wallet_balance]);

                    return responseJson(200, trans('admin.createMessageSuccess'),$bill);
                }
            }
        }
    }

    $total_di = $service_type->price * $dist;
    $tax_percentage = Setting::get('tax_percentage', 10);
    $tax = (($total_di + $min_wait_price + $service_type->fixed) * $tax_percentage) / 100;
    $total_dis = $total_di + $min_wait_price + $service_type->fixed + $tax;
    $total_dist = (float)number_format((float)$total_dis, 2, '.', '');
    $before_discount_total = $total_dist;

    $promoCodeUser = PromocodeUsage::where('user_id', $user->id)->where('status', '=', 'USED')->orderBy('created_at', 'desc')->first();
    // $user->update([
    //     'total_price' => $user->total_price + $before_discount_total,
    //     'total_trips' => $user->total_trips + 1,
    // ]);
    if ($promoCodeUser != null) {
        // فى حاله ان كان مستخدم برومو كود
        $promoCode = Promocode::findOrFail($promoCodeUser->promocode_id);
        //$promoCodeUser->update(['status' => 'EXPIRED']);

        //$discount = $total_dist / $promoCode->discount;
        $discount= $total_dist * ($promoCode->discount/100);
        $total = $total_dist - $discount + $tripTimePrice;

        $gift_percentage =(int)Setting::get('gift_percentage', 0);
        $requestPayments=[
            'request_id' => $request->Trip_id,
            'promocode_id' => $promoCode->id,
            'payment_mode' => 'CASH',
            'time_trip' => $time_trip,
            'time_trip_price' => $tripTimePrice,
            'distance' => $dist,
            'discount' => $discount,
            'WaitingTime' => $WaitingTime,
            'WaitingPrice' => $service_type->min_wait_price,
            'tax' => $tax,
            'price' => $service_type->price,
            'fixed' => $service_type->fixed,
            'min_wait_price' => $min_wait_price,
            'wallet' => $trip->use_wallet,
            'surge' => (float)$request->paid, // ما تم دفعه
            'before_discount_total' => $before_discount_total,
            'total' => ($total + $wallet_user) - ( ($total + $wallet_user) * ($gift_percentage/100) ) ,
            'gift'=>$gift_percentage,
            'total_before_gift'=> $total + $wallet_user ,
        ];

        $bill=[
            'fixed_price' => round($requestPayments['fixed'] * 10) / 10,//????? ??????
            'distance' => round($requestPayments['distance'] * 10) / 10,//???????
            'distance_price' => round($requestPayments['price'] * $requestPayments['distance'] * 10) / 10,//????? ???????
            'wattingTime' => round($requestPayments['WaitingTime']),//??? ????????
            'time_price' => round($requestPayments['min_wait_price'] * 10) / 10,//????? ????????
            'tripTime' => round($requestPayments['time_trip']),//??? ??????
            'watting_price' => round($requestPayments['time_trip_price'] *10) / 10,//????? ??? ??????
            'gift' => $gift_percentage,
            'total_before_gift' => round($requestPayments['total_before_gift'] * 10) / 10,//???????

            'tax' => round($requestPayments['tax'] * 10) / 10,//???????
            'total_price' => round($requestPayments['total'] * 10) / 10,//????????

        // watting_price: Math.round(response.body.data.WaitingPrice * 10) / 10,

            // 'discount_wallet' => round($requestPayments['discount_wallet'] * 10) / 10,//????? ?? ????
            'Trip_id' => $request->Trip_id
        ];



        //$requestPayments2 = UserRequestPayment::find($requestPayments->id);
        //$user_requests = UserRequests::findOrFail($requestPayments->request_id);
        $user_requests = UserRequests::findOrFail((int)$request->Trip_id);
        $provider = Provider::findOrFail($user_requests->provider->id);


        $revenue = Revenue::where('provider_id', $provider->id)
            ->where('status', '=', 'active')
            ->orderBy('created_at', 'DESC')->first();

        if ($provider->fleet != 0) {

            $company = Fleet::findOrFail($provider->fleet);

            $companySubscription = CompanySubscription::where('fleet_id', $company->id)
                ->where('status', '=', 'active')
                ->orderBy('created_at', 'DESC')->first();
            if ($companySubscription != null) {
                //$requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total]);
                return responseJson(200, trans('admin.createMessageSuccess'), $bill);
            } else {
                $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                $commission = ($requestPayments['total'] * $commissionWebSite) / 100;
                $provider_money = $requestPayments['total'] - $commission;
                //$requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                $wallet_balance = $company->wallet_balance - $commission;
                //$company->update(['wallet_balance' => $wallet_balance]);

                return responseJson(200, trans('admin.createMessageSuccess'), $bill);
            }
        } else {

            if ($revenue != null) {
                //$requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total]);
                return responseJson(200, trans('admin.createMessageSuccess'), $bill);
            } else {
                $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                $commission = ($requestPayments['total'] * $commissionWebSite) / 100;
                $provider_money = $requestPayments['total'] - $commission;
                //$requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                $wallet_balance = $provider->wallet_balance - $commission;
                //$provider->update(['wallet_balance' => $wallet_balance]);

                return responseJson(200, trans('admin.createMessageSuccess'), $bill);
            }
        }
    } else {
        // فى حاله ان لم يكن يستخدم البرومو كود

        $gift_percentage =(int)Setting::get('gift_percentage', 0);
        $requestPayments=[
            'request_id' => $request->Trip_id,
            'payment_mode' => 'CASH',
            'time_trip' => $time_trip,
            'time_trip_price' => $tripTimePrice,
            'WaitingTime' => $WaitingTime,
            'WaitingPrice' => $service_type->min_wait_price,
            'price' => $service_type->price,
            'fixed' => $service_type->fixed,
            'tax' => $tax,
            'min_wait_price' => $min_wait_price,
            'distance' => $dist,
            'wallet' => $trip->use_wallet,
            'before_discount_total' => $before_discount_total,
            'total' => ($total_dist + $wallet_user +$tripTimePrice) - ( ($total_dist + $wallet_user +$tripTimePrice) * ($gift_percentage/100) ) ,
            'gift'=>$gift_percentage,
            'total_before_gift'=> $total_dist + $wallet_user +$tripTimePrice,
        ];

        $bill=[
            'fixed_price' => round($requestPayments['fixed'] * 10) / 10,//????? ??????
            'distance' => round($requestPayments['distance'] * 10) / 10,//???????
            'distance_price' => round($requestPayments['price'] * $requestPayments['distance'] * 10) / 10,//????? ???????
            'wattingTime' => round($requestPayments['WaitingTime']),//??? ????????
            'time_price' => round($requestPayments['min_wait_price'] * 10) / 10,//????? ????????
            'tripTime' => round($requestPayments['time_trip']),//??? ??????
            'watting_price' => round($requestPayments['time_trip_price'] *10) / 10,//????? ??? ??????
            'gift' => $gift_percentage,
            'total_before_gift' => round($requestPayments['total_before_gift'] * 10) / 10,//???????

            'tax' => round($requestPayments['tax'] * 10) / 10,//???????
            'total_price' => round($requestPayments['total'] * 10) / 10,//????????

        // watting_price: Math.round(response.body.data.WaitingPrice * 10) / 10,

            // 'discount_wallet' => round($requestPayments['discount_wallet'] * 10) / 10,//????? ?? ????
            'Trip_id' => $request->Trip_id
        ];



        //$requestPayments2 = UserRequestPayment::find($requestPayments->id);
        //$user_requests = UserRequests::findOrFail($requestPayments->request_id);
        $user_requests = UserRequests::findOrFail((int)$request->Trip_id);
        $provider = Provider::findOrFail($user_requests->provider->id);

        $revenue = Revenue::where('provider_id', $provider->id)
            ->where('status', '=', 'active')
            ->orderBy('created_at', 'DESC')->first();


        if ($provider->fleet != 0) {
            // فى حاله ان مكنش مستخدم برومو كود وليه شركه
            $company = Fleet::findOrFail($provider->fleet);

            $companySubscription = CompanySubscription::where('fleet_id', $company->id)
                ->where('status', '=', 'active')
                ->orderBy('created_at', 'DESC')->first();

            if ($companySubscription != null) {
                //$requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total]);
                return responseJson(200, trans('admin.createMessageSuccess'), $bill);
            } else {
                $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                $commission = ($requestPayments['total'] * $commissionWebSite) / 100;
                $provider_money = $requestPayments['total'] - $commission;
                //$requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                $wallet_balance = $company->wallet_balance - $commission;
                //$company->update(['wallet_balance' => $wallet_balance]);

                return responseJson(200, trans('admin.createMessageSuccess'), $bill);
            }
        } else {

            if ($revenue != null) {
                //$requestPayments->update(['commision' => 0, 'provider_money' => $requestPayments->total]);
                return responseJson(200, trans('admin.createMessageSuccess'), $bill);
            } else {
                $commissionWebSite = (int)Setting::get('commission_percentage', 10);
                $commission = ($requestPayments['total'] * $commissionWebSite) / 100;
                $provider_money = $requestPayments['total'] - $commission;
                //$requestPayments->update(['commision' => $commission, 'provider_money' => $provider_money]);

                $wallet_balance = $provider->wallet_balance - $commission;
                //$provider->update(['wallet_balance' => $wallet_balance]);

                return responseJson(200, trans('admin.createMessageSuccess'), $bill);
            }
        }
    }


   }

   public function get_andriod_version(){
        $a=Settings::find(111);
        $version=$a->value;
        $b=Settings::find(112);
        $importance=(int)$b->value;
        $x=[
            'version'=>$version,
            'importance'=>$importance
        ];
        return responseJson(200, "Done", $x);
        //return "Aloo";
   }

   public function post_andriod_version(Request $request){
    $x=Settings::find(106);
    $x->value=$request->version;
    $x->save();
    return "Done" ;
    //return "Aloo";
}

    public function rateProvider(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'trip_id' => 'required|exists:user_requests,id',
            'rating' => 'required|in:1,2,3,4,5',
            'comment' => 'required',
        ]);

        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }

        //return Auth::user()->id;



        //return $num_of_requests;

        //return $request->user() ;
        $user_requests = UserRequests::findOrFail($request->trip_id); //trip
        $user_id = $user_requests->user_id;
        $num_of_requests = UserRequests::where('user_id', Auth::user()->id)->where('status' ,'=' ,'COMPLETED')->count();
        $user=User::find($user_id);

        $provider = $request->user(); // provider auth
        if ($user_requests->status == 'COMPLETED') {
            if ($provider->id == $user_requests->provider_id) {
                if ($user_requests->rating == null) {
                    $rate = UserRequestRating::create([
                        'provider_comment' => $request->comment,
                        'provider_rating' => $request->rating,
                    ]);
                    $rate->request_id = $request->trip_id;
                    $rate->provider_id = $provider->id;
                    $rate->user_id = $user_requests->user_id;
                    $rate->save();

                    $provider=Provider::find(Auth::user()->id);
                    $user->rating= ( ($user->rating * $num_of_requests) + (1 * $request->rating) ) / ($num_of_requests + 1)  ;
                    $user->save();
                    //return $provider->rating;

                    return responseJson(1, 'تمت العمليه بنجاح', $rate);

                } else {
                    $rate = $user_requests->rating->update([
                        'provider_comment' => $request->comment,
                        'provider_rating' => $request->rating,
                    ]);

                    $provider=Provider::find(Auth::user()->id);
                    $user->rating= ( ($user->rating * $num_of_requests) + (1 * $request->rating) ) / ($num_of_requests + 1)  ;
                    $user->save();
                    //return $provider->rating;

                    return responseJson(1, 'تمت العمليه بنجاح', $rate);
                }
            } else {
                return responseJson(0, 'لا يمكنك التعليق على الرحله');
            }
        } else {
            return responseJson(0, 'هذا الطلب لم يكتمل بعد');
        }
    }


    public function index(Request $request)
    {
        $this->validate($request, [
            'device_mac' => 'required',
        ]);
        if (auth()->user()->device_mac == $request->device_mac) {
            try {

                auth()->user()->service = ProviderService::where('provider_id', auth()->user()->id)
                    ->with('service_type')
                    ->first();
                //auth()->user()->fleet = Fleet::find(auth()->user()->fleet);
                auth()->user()->currency = Setting::get('currency', '$');
                auth()->user()->sos = Setting::get('sos_number', '911');
                $trip= UserRequests::where('provider_id', auth()->user()->id)->orderBy('created_at', 'desc')->first();
                if($trip != null){
                    $userID=$trip->user_id;
                    $user=User::find($userID);
                    auth()->user()->trip=$trip;
                    auth()->user()->user=$user;
                }
                return auth()->user();

            } catch (Exception $e) {
                return $e->getMessage();
            }
        } else {
            return response()->json(['error' => 'api.no'], 401);
        }
    }

    public function newDetails(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'token' => 'required',
            'device_mac' => 'required',
            'lang' => 'nullable|in:ar,en',
        ]);

        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }
        if (auth()->user()->device_mac == $request->device_mac) {
            $provider = ProviderDevice::with('provider')->with('service_type')->where('token', $request->token)->first();
            if ($provider) {
                $provider->provider;
                $provider->provider->fleet = Fleet::find(Auth::user()->fleet);
                $provider->provider->currency = Setting::get('currency', '$');
                $provider->provider->sos = Setting::get('sos_number', '911');
                if ($request->lang == "ar") {
                    return responseJson(400, 'تمت العمليه بنجاح', $provider);
                } elseif ($request->lang == "en") {
                    return responseJson(400, 'Successfully', $provider);
                } else {
                    return responseJson(400, 'تمت العمليه بنجاح', $provider);
                }
            }
            return responseJson(0, 'لا توجد بيانات');
        } else {
            return response()->json(['error' => 'api.no'], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function checkAccount(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'mail' => 'required|email|exists:providers,email',
        ]);
        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
        }
        try {
            $this->checkData();
            $Provider = Provider::where(['email' => $request->mail])->first();
            $provider_cars = CarProvider::where('provider_id', $Provider->id)->count();
            $provider_car = CarProvider::where('provider_id', $Provider->id)->where('status', '=', 'not_active')->first();
            $car_provider = CarProvider::where('provider_id', $Provider->id)->where('status', '!=', 'not_active')->first();
            if ($Provider != null) {
                //&& $Provider->criminal_feat != null && $Provider->drug_analysis_licence != null
                if ($Provider->avatar != null && $Provider->driver_licence_back != null && $Provider->driver_licence_front != null && $Provider->identity_front != null && $Provider->identity_back != null ) {
                    if (count($Provider->cars) > 0) {
                        if ($Provider->otp == 0) {
                            if (($car_provider != null && $provider_cars > 1) || $provider_car == null) {
                                if ($Provider->status != 'onboarding') {
                                    foreach ($Provider->cars as $items) {
                                        if ($items->car_front == null || $items->car_back == null || $items->car_left == null || $items->car_right == null || $items->car_licence_front == null || $items->car_licence_back == null) {
                                            $Provider->device_mac = null;
                                            $Provider->save();
                                            return response()->json('car_id=' . $items->id);
                                        }
                                    }
                                    return response()->json(true);
                                } else {
                                    return response()->json('Not Active Account');
                                }
                            } else {
                                return response()->json('Not Car Provider');
                            }
                        } else {
                            return response()->json('Not Active Email');
                        }
                    } else {
                        return response()->json('Car Not');
                    }
                } else {
                    return response()->json('Provider Not');
                }
            } else {
                return response()->json(false);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Account Error'], 404);
        }
    }

    public function activeEmail(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'email' => 'required|email|exists:providers,email',
        ]);

        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
        }
        $Provider = Provider::where(['email' => $request->email])->first();

        if ($Provider) {
            $code = rand(111111, 999999);
            $update = $Provider->update(['otp' => $code]);
            if ($update) {

                // Mail::to($Provider->email)
                //     ->bcc(Admin::find(28)->email)
                //     ->send(new alBazAccountConfirmation($code));

                    $admin=Admin::find(28);
                    config(['mail.username' => $admin->email]);
                    config(['mail.password'=>$admin->email_password]);
                    //dd( $admin->email, $admin->email_password);

                    Mail::to($Provider->email)
                    ->bcc('ailbaza156@gmail.com')
                    //->bcc(Admin::find(28)->email)
                    ->send(new alBazAccountConfirmation($code));

                return response()->json(true);

            } else {
                return response()->json(['message' => trans("admin.Something Went Wrong")], 422);
            }
        } else {
            return responseJson(422, trans("admin.Something Went Wrong"));
        }
    }

    public function fawzyActiveEmail(Request $request){

        $validator = validator()->make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
        }
        $oldProvider = Otp::where(['email' => $request->email])->first();

        if ($oldProvider) {
            $code = rand(111111, 999999);
            $update = $oldProvider->update(['otp' => $code]);
            if ($update) {

                // Mail::to($Provider->email)
                //     ->bcc(Admin::find(28)->email)
                //     ->send(new alBazAccountConfirmation($code));

                    $admin=Admin::find(28);
                    config(['mail.username' => $admin->email]);
                    config(['mail.password'=>$admin->email_password]);
                    //dd( $admin->email, $admin->email_password);

                    Mail::to($oldProvider->email)
                    ->bcc('ailbaza156@gmail.com')
                    //->bcc(Admin::find(28)->email)
                    ->send(new alBazAccountConfirmation($code));

                return response()->json(true);

            } else {
                return response()->json(['message' => trans("admin.Something Went Wrong")], 422);
            }
        } elseif(!$oldProvider) {
            //return responseJson(422, trans("admin.Something Went Wrong"));
            $code = rand(111111, 999999);
            $newProvider= Otp::create([
                'email'=>$request->email,
                'otp'=>$code,
            ]);

            // $newProvider->email=$request->email;
            // $newProvider->otp=$code;

            if ($newProvider) {

                // Mail::to($Provider->email)
                //     ->bcc(Admin::find(28)->email)
                //     ->send(new alBazAccountConfirmation($code));

                    $admin=Admin::find(28);
                    config(['mail.username' => $admin->email]);
                    config(['mail.password'=>$admin->email_password]);
                    //dd( $admin->email, $admin->email_password);

                    Mail::to($newProvider->email)
                    ->bcc('ailbaza156@gmail.com')
                    //->bcc(Admin::find(28)->email)
                    ->send(new alBazAccountConfirmation($code));

                return response()->json(true);

            } else {
                return response()->json(['message' => trans("admin.Something Went Wrong")], 422);
            }
        }else{
            return responseJson(422, trans("admin.Something Went Wrong"));
        }
    }

    public function verificationEmail(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'email' => 'required|exists:providers,email',
            'code' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
        }
        $provider = Provider::where(['email' => $request->email, 'otp' => $request->code])->first();
        if ($provider) {
            if ($provider->otp != 0) {
                $provider->otp = 0;
                $provider->status = 'onboarding';
                $provider->save();
                return response()->json(true);
            }
            else {
                return responseJson(422, trans('api.Code it cannot be equal to Zero'));
            }
        } else {
            return responseJson(422, trans('api.providerNotFound'));
        }
    }

    public function fawzyVerificationEmail(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'email' => 'required|exists:otps,email',
            'code' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
        }
        $provider = Otp::where(['email' => $request->email, 'otp' => $request->code])->first();
        if ($provider) {
            // if ($provider->otp != 0) {
                // $provider->otp = 0;
                // $provider->status = 'onboarding';
                // $provider->save();
                return response()->json(true);
            //}
            // else {
            //     return responseJson(422, trans('api.Code it cannot be equal to Zero'));
            // }
        } else {
            return responseJson(422, trans('api.providerNotFound'));
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|min:3|max:255',
            'last_name' => 'required|min:3|max:255',
            'mobile' => 'required||between:13,13',
            'avatar' => 'mimes:jpeg,jpg,bmp,png',
            'language' => 'required|max:255',
            'address' => 'required|min:3|max:255',
            'address_secondary' => 'required|min:10|max:255',
            'city' => 'required|min:5|max:255',
            'country' => 'required|min:3|max:255',
            'postal_code' => 'required|min:16|max:255',
            'car_number' => 'min:6|max:8',
            'car_history' => 'min:4|max:4',
        ]);

        try {

            $Provider = Auth::user();

            if ($request->has('first_name'))
                $Provider->first_name = base64_encode($request->first_name);

            if ($request->has('last_name'))
                $Provider->last_name = base64_encode($request->last_name);

            if ($request->has('mobile'))
                $Provider->mobile = $request->mobile;

            // if ($request->hasFile('avatar')) {
            //     Storage::delete($Provider->avatar);
            //     $Provider->avatar = $request->avatar->store('provider/profile');
            // }

            if ($request->hasFile('avatar')) {
                $avatar = $request->avatar;
                $code = rand(111111111, 999999999);
                $avatar_new_name = time() . $code."pp";
                $avatar->move('uploads/provider', $avatar_new_name);

                $Provider->avatar = 'uploads/provider/' . $avatar_new_name;
                $Provider->save();
            }

            if ($request->has('car_type_id')) {
                if ($Provider->service) {
                    if ($Provider->service->car_type_id != $request->car_type) {
                        $Provider->status = 'banned';
                    }
                    $ProviderService = ProviderService::find(Auth::user()->id);
                    $ProviderService->car_type_id = $request->car_type;
                    $ProviderService->car_number = $request->car_number;
                    $ProviderService->car_history = $request->car_history;
                    $ProviderService->save();
                } else {
                    ProviderService::create([
                        'provider_id' => $Provider->id,
                        'car_type_id' => $request->car_type,
                        'car_number' => $request->car_number,
                        'car_history' => $request->car_history,
                    ]);
                    $Provider->status = 'banned';
                }
            }

            if ($Provider->profile) {
                $Provider->profile->update([
                    'language' => $request->language ?: $Provider->profile->language,
                    'address' => $request->address ?: $Provider->profile->address,
                    'address_secondary' => $request->address_secondary ?: $Provider->profile->address_secondary,
                    'city' => $request->city ?: $Provider->profile->city,
                    'country' => $request->country ?: $Provider->profile->country,
                    'postal_code' => $request->postal_code ?: $Provider->profile->postal_code,
                    'car_number' => $request->car_number ?: $Provider->profile->car_number,
                    'car_history' => $request->car_history ?: $Provider->profile->car_history,
                ]);
            } else {
                ProviderProfile::create([
                    'provider_id' => $Provider->id,
                    'language' => $request->language,
                    'address' => $request->address,
                    'address_secondary' => $request->address_secondary,
                    'city' => $request->city,
                    'country' => $request->country,
                    'postal_code' => $request->postal_code,
                    'car_number' => $request->car_number,
                    'car_history' => $request->car_history,
                ]);
            }

            $Provider->save();

            return redirect(route('provider.profile.index'))->with('flash_success', trans('admin.editMessageSuccess'));
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => trans('admin.Provider Not Found')], 404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $car_models = DB::table('car_models')->get();
        return view('provider.profile.index', compact('car_models'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */


    public function update(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'first_name' => 'nullable|max:255',
            'last_name' => 'nullable|max:255',
            'avatar' => 'nullable|mimes:jpeg,png,png',
            'language' => 'nullable|max:255',
            'address' => 'nullable|max:255',
            'address_secondary' => 'nullable|max:255',
            'city' => 'nullable|max:255',
            'country' => 'nullable|max:255',
            'postal_code' => 'nullable|max:255',
        ]);
        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }
        try {

            $Provider = Auth::user();

            if ($request->has('first_name'))
                $Provider->first_name = $request->first_name;

            if ($request->has('last_name'))
                $Provider->last_name = $request->last_name;

            if ($request->has('mobile'))
                $Provider->mobile = $request->mobile;

            if ($request->hasFile('avatar')) {
                $avatar = $request->avatar;
                $code = rand(111111111, 999999999);
                $avatar_new_name = time() .$code."pp";
                $avatar->move('uploads/provider/', $avatar_new_name);

                $Provider->avatar = 'uploads/provider/' . $avatar_new_name;
                $Provider->save();
            }

            if ($Provider->profile) {
                $Provider->profile->update([
                    'language' => $request->language ?: $Provider->profile->language,
                    'address' => $request->address ?: $Provider->profile->address,
                    'address_secondary' => $request->address_secondary ?: $Provider->profile->address_secondary,
                    'city' => $request->city ?: $Provider->profile->city,
                    'country' => $request->country ?: $Provider->profile->country,
                    'postal_code' => $request->postal_code ?: $Provider->profile->postal_code,
                ]);
            } else {
                ProviderProfile::create([
                    'provider_id' => $Provider->id,
                    'language' => $request->language,
                    'address' => $request->address,
                    'address_secondary' => $request->address_secondary,
                    'city' => $request->city,
                    'country' => $request->country,
                    'postal_code' => $request->postal_code,
                ]);
            }

            $Provider->save();

            return responseJson(200, trans('api.editMessageSuccess'), $Provider);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Provider Not Found!'], 404);
        }
    }

    public function cars()
    {
        $cars = CarModel::where('status', 1)->get();

        if (count($cars) > 0) {
            return responseJson(200, trans('admin.The operation was successful'), $cars);
            //return ["msg"=>'hello'];
        }
        return responseJson(422, trans('admin.No Cars'));
    }

    public function mycars(/*Request $request*/)
    {
        // $validator = validator()->make($request->all(), [
        //     'mail' => 'required|email|exists:providers,email',
        // ]);
        // if ($validator->fails()) {
        //     return responseJson(422, $validator->errors()->first(), $validator->errors());
        // }
        try {

        //  $Provider = Provider::where(['email' => $request->mail])->first();
        $Provider = Auth::user();
        $provider_car = CarProvider::where('provider_id', $Provider->id)->where('status', '!=', 'not_active')->get();

        $carss=[];
        foreach ($provider_car as $car) {
            $carss=Car::where('id', $car->car_id)->first();
        }

        if (count($provider_car) > 0) {
            // return responseJson(200, trans('admin.The operation was successful'), $carss);
            return response()->Json( $carss);
        }
        return responseJson(422, trans('admin.No Cars'));
      }
      catch (ModelNotFoundException $e) {
        return response()->json(['error' => 'Account Error'], 404);
        }
    }

    public function imageUpload(Request $request)
    {
        $validator = validator()->make($request->all(), [
            //|mimes:jpg,jpeg,png
            //'id' => 'nullable|numeric|exists:providers,id',
            'email' => 'required|exists:providers,email',
            'avatar' => 'required|mimes:jpg,jpeg,png',
            'driver_licence_front' => 'required|mimes:jpg,jpeg,png',
            'driver_licence_back' => 'required|mimes:jpg,jpeg,png',
            'identity_front' => 'required|mimes:jpg,jpeg,png',
            'identity_back' => 'required|mimes:jpg,jpeg,png',
            //'criminal_feat' => 'required|mimes:jpg,jpeg,png',
            'criminal_feat' => 'nullable',
            //'drug_analysis_licence' => 'required|mimes:jpg,jpeg,png',
            'drug_analysis_licence' => 'nullable',
        ]);
        if ($validator->fails()) {
            return responseJson(422, $validator->errors()->first(), $validator->errors());
        }
        $provider = Provider::where(['email' => $request->email])->first();
        if ($provider) {
            // if (!empty($request->avatar)) $provider->avatar = Helper::upload_picture($request->file('avatar'));
            // if (!empty($request->driver_licence_front)) $provider->driver_licence_front = Helper::upload_picture($request->file('driver_licence_front'));
            // if (!empty($request->driver_licence_back)) $provider->driver_licence_back = Helper::upload_picture($request->file('driver_licence_back'));
            // if (!empty($request->identity_front)) $provider->identity_front = Helper::upload_picture($request->file('identity_front'));
            // if (!empty($request->identity_back)) $provider->identity_back = Helper::upload_picture($request->file('identity_back'));
            // if (!empty($request->criminal_feat)) $provider->criminal_feat = Helper::upload_picture($request->file('criminal_feat'));
            // if (!empty($request->drug_analysis_licence)) $provider->drug_analysis_licence = Helper::upload_picture($request->file('drug_analysis_licence'));

            if (!empty($request->avatar)){
                $picture = $request->avatar;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."pp";
                $picture->move('uploads/provider/', $avatar_new_name);
                $provider->avatar ='uploads/provider/' . $avatar_new_name;
            }

            //if (!empty($request->driver_licence_front)) $provider->driver_licence_front = Helper::upload_picture($request->file('driver_licence_front'));

            if (!empty($request->driver_licence_front)){
                $picture = $request->driver_licence_front;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."dlf";
                $picture->move('uploads/provider/', $avatar_new_name);
                $provider->driver_licence_front ='uploads/provider/' . $avatar_new_name;
            }

            //if (!empty($request->driver_licence_back)) $provider->driver_licence_back = Helper::upload_picture($request->file('driver_licence_back'));

            if (!empty($request->driver_licence_back)){
                $picture = $request->driver_licence_back;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."dlb";
                $picture->move('uploads/provider/', $avatar_new_name);
                $provider->driver_licence_back ='uploads/provider/' . $avatar_new_name;
            }

            //if (!empty($request->identity_front)) $provider->identity_front = Helper::upload_picture($request->file('identity_front'));
            if (!empty($request->identity_front)){
                $picture = $request->identity_front;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."if";
                $picture->move('uploads/provider/', $avatar_new_name);
                $provider->identity_front ='uploads/provider/' . $avatar_new_name;
            }

            //if (!empty($request->identity_back)) $provider->identity_back = Helper::upload_picture($request->file('identity_back'));
            if (!empty($request->identity_back)){
                $picture = $request->identity_back;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."ib";
                $picture->move('uploads/provider/', $avatar_new_name);
                $provider->identity_back ='uploads/provider/' . $avatar_new_name;
            }

            //if (!empty($request->criminal_feat)) $provider->criminal_feat = Helper::upload_picture($request->file('criminal_feat'));
            if (!empty($request->criminal_feat)){
                $picture = $request->criminal_feat;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."cf";
                $picture->move('uploads/provider/', $avatar_new_name);
                $provider->criminal_feat ='uploads/provider/' . $avatar_new_name;
            }

            //if (!empty($request->drug_analysis_licence)) $provider->drug_analysis_licence = Helper::upload_picture($request->file('drug_analysis_licence'));
            if (!empty($request->drug_analysis_licence)){
                $picture = $request->drug_analysis_licence;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."dal";
                $picture->move('uploads/provider/', $avatar_new_name);
                $provider->drug_analysis_licence ='uploads/provider/' . $avatar_new_name;
            }

            $provider->save();
            return responseJson(200, trans('admin.editMessageSuccess'), ['Provider' => $provider]);
        }
        return responseJson(422, trans('admin.Provider Not Found'));
    }


    public function carService(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'car_id' => 'required|numeric|exists:car_models,id',
            'device_mac' => 'required',
        ]);

        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }

        $providerService = CarModel::find($request->car_id);
        if (auth()->user()->device_mac == $request->device_mac) {
            $provider = $request->user();
            $provider_service = ProviderService::create([
                'provider_id' => $provider->id,
                'service_type_id' => $providerService->service_id,
            ]);

            if ($provider) {
                $provider->update(['car_id' => $providerService->id]);

                $provider->save();
                return responseJson(200, trans('admin.createMessageSuccess'), $provider);
            }
            return responseJson(422, 'لا يوجد سائق');
        } else {
            return response()->json(['error' => 'api.no'], 401);
        }
    }

    /**
     * Update latitude and longitude of the user.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

     public function location(Request $request)
    {
        $this->validate($request, [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($Provider = Auth::user()) {

            $Provider->latitude = $request->latitude;
            $Provider->longitude = $request->longitude;
            $Provider->save();

            return response()->json(['message' => 'Location Updated successfully!']);

        } else {
            return response()->json(['error' => 'Provider Not Found!']);
        }
    }

    /**
     * Toggle service availability of the provider.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function available(Request $request)
    {

        $this->validate($request, [
            'service_status' => 'required|in:active,offline',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'device_mac' => 'required',
        ]);

        $Provider = auth()->user();

        if ($Provider->device_mac == $request->device_mac) {
            if ($Provider->status != 'banned') {
                if ($Provider->status == 'approved' && $request->service_status == 'offline') {
                    $Provider->service->update(['status' => $request->service_status]);
                } elseif ($request->service_status == 'active') {
                    $Provider->service->update(['status' => $request->service_status]);
                    $Provider->update(['latitude' => $request->latitude, 'longitude' => $request->longitude]);
                } else {
                    return response()->json(['error' => trans('api.You account has not been approved for driving')]);
                }
                return $Provider->service;
            } else {
                return responseJson(420, trans('api.This is account banded contact the company'));
            }
        } else {
            return response()->json(['error' => 'api.no'], 401);
        }
    }

    /**
     * Update password of the provider.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function password(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed',
            'password_old' => 'required',
            'device_mac' => 'required',
            'lang' => 'nullable|in:ar,en',
        ]);
        if (auth()->user()->device_mac == $request->device_mac) {

            $Provider = Auth::user();

            if (password_verify($request->password_old, $Provider->password)) {
                $Provider->password = bcrypt($request->password);
                $Provider->save();

            //return response()->json(['message' => 'Password changed successfully!']);
                if ($request->lang == "ar") {
                    return response()->json(['message' => trans('api.Password changed successfully! Ar')]);
                } elseif ($request->lang == "en") {
                    return response()->json(['message' => trans('api.Password changed successfully! En')]);
                } else {
                    return response()->json(['message' => trans('api.Password changed successfully! Ar')]);
                }
            } else {
            //return response()->json(['error' => 'Please enter correct password'], 422);
                if ($request->lang == "ar") {
                    return response()->json(['error' => trans('api.Please enter correct password Ar')], 422);
                } elseif ($request->lang == "en") {
                    return response()->json(['error' => trans('api.Please enter correct password En')], 422);
                } else {
                    return response()->json(['error' => trans('api.Please enter correct password Ar')], 422);
                }
            }
        } else {
            return response()->json(['error' => 'api.no'], 401);
        }
    }

    /**
     * Show providers daily target.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function target(Request $request)
    {
        $this->validate($request, [
            'device_mac' => 'required',
            'lang' => 'nullable|in:ar,en',
        ]);
        if (auth()->user()->device_mac == $request->device_mac) {

            try {

                $Rides = UserRequests::where('provider_id', Auth::user()->id)
                    ->where('status', 'COMPLETED')
                    ->where('created_at', '>=', Carbon::today())
                    ->with('payment', 'service_type')
                    ->get();

                return response()->json([
                    'rides' => $Rides,
                    'rides_count' => $Rides->count(),
                    'target' => Setting::get('daily_target', '0')
                ]);

            } catch (Exception $e) {
                //return response()->json(['message' => "Something Went Wrong"]);
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

    public function App_Details(Request $request)
    {

        $App_Name = Setting::get('site_title', 'Al Baz');
        $App_Icon = Setting::get('site_icon', 'http://ailbaz.com/public/logo-black.png');
        $App_Splash = Setting::get('splash', 'http://ailbaz.com/public/logo-black.png');
        $App_Logo = Setting::get('site_logo', 'http://ailbaz.com/public/logo-black.png');
        $App_Status = Setting::get('app_status', '1');
        $App_Offline_Msg = Setting::get('offline_msg', 'System is under Maintenance');
        $Phone_Number = Setting::get('contact_number', '');
        $Email = Setting::get('contact_email', '');
        $Interval_Time = Setting::get('interval_time', '3000');
        $time_left_to_respond = Setting::get('provider_select_timeout', '180');
        $provider_search_radius = Setting::get('provider_search_radius', '200');
        $app_msg = Setting::get('app_msg', '');

        return response()->json([
            'App_Name' => $App_Name,
            'App_Icon' => $App_Icon,
            'App_Logo' => $App_Logo,
            'App_Splash' => $App_Splash,
            'App_Status' => $App_Status,
            'App_Offline_Msg' => $App_Offline_Msg,
            'App_Msg' => $app_msg,
            'Phone_Number' => $Phone_Number,
            'Email' => $Email,
            'Interval_Time' => $Interval_Time,
            'Time_Left_To_Respond' => $time_left_to_respond,
            'Searching_Range' => $provider_search_radius,
            //'accessToken' => $accessToken,
        ]);
    }

   /*public function updateLocation(Request $request)
   {
       $validator = validator()->make($request->all(), [
           'token' => 'required',
           'latitude' => 'required|numeric',
           'longitude' => 'required|numeric',
       ]);

       if ($validator->fails()) {
           return responseJson(0, $validator->errors()->first(), $validator->errors());
       }
       $provider = ProviderDevice::with('provider')->where('token' , $request->token)->first();
       if ($provider) {
           $provider->provider->update(['latitude' => $request->latitude, 'longitude' => $request->longitude]);


           return responseJson(1, 'تمت العمليه بنجاح', $provider);
       }
       return responseJson(0, 'لا توجد بيانات');
   }*/

    public function updateLocation(Request $request)
    {
        $provider = Provider::find(Auth::user()->id);
        $validator = validator()->make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'device_mac' => 'required',
        ]);

        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }
        if (auth()->user()->device_mac == $request->device_mac) {
            if ($provider) {
                $provider->update(['latitude' => $request->latitude, 'longitude' => $request->longitude]);

                return responseJson(1, 'تمت العمليه بنجاح', $provider);
            }
            return responseJson(0, 'لا توجد بيانات');
        } else {
            return response()->json(['error' => 'api.no'], 401);
        }
    }

    static function checkData()
    {
        // update promo code expiration time
        $promocodes = Promocode::all();
        foreach ($promocodes as $promo) {
            if (date("Y-m-d") > $promo->expiration) {
                if ($promo->status == 'ADDED') {
                    $promo->status = 'EXPIRED';
                    $promo->save();
                }
                $promo_users = PromocodeUsage::where('status', '!=', 'EXPIRED')->where('promocode_id', $promo->id)->get();
                foreach ($promo_users as $promo_user) {
                    $promo_user->status = 'EXPIRED';
                    $promo_user->save();
                }
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////////////
        // update revenues expiration time


        // update revenues expiration time
        $companySubscriptions = CompanySubscription::all();
        foreach ($companySubscriptions as $companySubscription) {
            if (date("Y-m-d") > $companySubscription->to) {
                if ($companySubscription->status != 'time_finish') {
                    $companySubscription->status = 'time_finish';
                    $companySubscription->save();
                }
            } elseif (date("Y-m-d") >= $companySubscription->from) {
                if ($companySubscription->status != 'active') {
                    $companySubscription->status = 'active';
                    $companySubscription->save();
                }
            }
        }
    }
}
