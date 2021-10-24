<?php

namespace App\Http\Controllers;

use App\Promocode;
use App\PromocodeUsage;
use App\ServiceType;
use App\User;
use App\Otp;
use App\Provider;
use App\UserRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class  HomeController extends Controller
{
    protected $UserAPI;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserApiController $UserAPI)
    {
        $this->middleware('auth');
        $this->UserAPI = $UserAPI;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function alo(){
        // if(File::exists("uploads/user/1615728250")) {
        //     File::delete("uploads/user/1615728250");
        // }

        // $x=Otp::all();
        // return $x;

        // $baz=User::find(4);
        // $baz->id_url=str_random(100);
        // $baz->save();
        // return true;

        //return view('map');

        $provider=Provider::find(187);
        return $provider->service->status;

    }

     public function index()
    {
        //return "Alo";
        $Response = $this->UserAPI->request_status_check()->getData();
        if (empty($Response->data)) {
            if ($serviceList = ServiceType::all()) {
                $services = $serviceList;
                return view('user.dashboard', compact('services'));
            } else {
                return view('user.ride.waiting')->with('request', $Response->data[0]);
            }
        }
        $services = ServiceType::all();
        return view('user.dashboard', compact('services'));
        // $Response = $this->UserAPI->request_status_check()->getData();
        // if (empty($Response->data)) {
        //     if ($serviceList = ServiceType::all()) {
        //         $services = $serviceList;
        //         return view('user.dashboard', compact('services'));
        //     } else {
        //         return view('user.ride.waiting')->with('request', $Response->data[0]);
        //     }
        // }
    }

    /**
     * Show the application profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return view('user.account.profile');
    }


    /**
     * Show the application profile.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit_profile()
    {
        return view('user.account.edit_profile');
    }

    /**
     * Update profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_profile(Request $request)
    {
        return $this->UserAPI->update_profile($request);
    }

    /**
     * Show the application change password.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */



    public function change_password()
    {
        return view('user.account.change_password');
    }

    /**
     * Change Password.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_password(Request $request)
    {
        return $this->UserAPI->change_password($request);
    }

    /**
     * Trips.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
//    public function trips()
//    {
//        $trips = $this->UserAPI->trips();
//        // dd($trips);
//        return view('user.ride.trips',compact('trips'));
//    }

    public function trips()
    {

        if (User::find(auth()->user()->id)) {
            $UserRequests = UserRequests::UserTrips(auth()->user()->id)->get();
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
            $trips = $UserRequests;
            //return compact('trips');
            return view('user.ride.trips', compact('trips'));
        }
    }

    /**
     * Payment.
     *
     * @return \Illuminate\Http\Response
     */
    public function payment()
    {
        $cards = (new Resource\CardResource)->index();
        return view('user.account.payment', compact('cards'));
    }

    /**
     * Wallet.
     *
     * @return \Illuminate\Http\Response
     */
    public function wallet(Request $request)
    {
        $cards = (new Resource\CardResource)->index();
        return view('user.account.wallet', compact('cards'));
    }

    /**
     * Promotion.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
//    public function promotions_index(Request $request)
//    {
//        $promocodes = $this->UserAPI->promocodes();
//        return view('user.account.promotions', compact('promocodes'));
//    }

    public function promotions_index()
    {
        $this->check_expiry();

        $promocodes = PromocodeUsage::Active()
            ->where('user_id', Auth::user()->id)
            ->with('promocode')
            ->get();
        return view('user.account.promotions', compact('promocodes'));
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
     * Add promocode.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function promotions_store(Request $request)
    {
        return $this->UserAPI->add_promocode($request);
    }

    /**
     * Upcoming Trips.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
//    public function upcoming_trips()
//    {
//        $trips = $this->UserAPI->upcoming_trips();
//        return view('user.ride.upcoming',compact('trips'));
//    }

    public function upcoming_trips()
    {
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
        $trips = $UserRequests;
        return view('user.ride.upcoming', compact('trips'));
    }


    public function condition(ٌRequest $request)
    {


    }
}
