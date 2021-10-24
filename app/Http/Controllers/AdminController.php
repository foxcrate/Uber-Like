<?php

namespace App\Http\Controllers;

use App\CompanySubscription;
use App\Http\Controllers\ProviderResources\ProfileController;
use App\Promocode;
use App\ProviderUserRequests;
use App\Revenue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Helpers\Helper;

use Auth;
use Setting;
use Exception;
use \Carbon\Carbon;
use App\ServiceConditions;
use App\User;
use App\Box;
use App\Governorate;
use App\Fleet;
use App\Admin;
use App\Provider;
use App\UserPayment;
use App\ServiceType;
use App\UserRequests;
use App\ProviderService;
use App\UserRequestRating;
use App\UserRequestPayment;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Dashboard.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function dashboard()
    {
        try{
            ProfileController::checkData();
            $rides = UserRequests::has('user')->orderBy('id','desc')->get();
            $cancel_rides = UserRequests::where('status','CANCELLED');
            $scheduled_rides = UserRequests::where('status','SCHEDULED')->count();
            $provider_cancelled =  UserRequests::where('status','CANCELLED')->where('cancelled_by','PROVIDER');
            $user_cancelled = UserRequests::where('status','CANCELLED')->where('cancelled_by','USER')->count();
            $cancel_rides = $cancel_rides->count();
            $service = ServiceType::count();
            $fleet = Fleet::count();
            $commission_percentage = Setting::get('commission_percentage', '0');
            $revenue = UserRequestPayment::sum('total');
            $company_subscription = CompanySubscription::where('status', '=', 'active')->count();
            $provider_subscription = Revenue::where('status', '=', 'active')->count();
            $providers = Provider::take(10)->orderBy('rating','desc')->get();
            return view('admin.dashboard',compact('providers','fleet','scheduled_rides','service','rides',
                'user_cancelled','provider_cancelled','cancel_rides','revenue','commission_percentage','company_subscription','provider_subscription'));
        }
        catch(Exception $e){
            return redirect()->route('admin.user.index')->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }


    /**
     * Heat Map.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function heatmap()
    {
        try{
            $rides = UserRequests::has('user')->orderBy('id','desc')->get();
            $providers = Provider::take(10)->orderBy('rating','desc')->get();
            $users = UserRequests::select()->get();
            return view('admin.heatmap',compact('providers','rides','users'));
        }
        catch(Exception $e){
            return redirect()->route('admin.user.index')->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }

    public function get_governments(){

        $governments=Governorate::all();
        return $governments;

    }

    public function post_governments(Request $request){

    }

    public function heatmap2()
    {
        try{
            $Providers = Provider::select()->get();
            return view('admin.heatmapDR',compact('Providers'));
        }
        catch(Exception $e){
            return redirect()->route('admin.user.index')->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }
    /**
     * Map of all Users and Drivers.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function map_index()
    {
        $users = '';
        $providers = Provider::whereHas('service',function($service){
            $service->where(['status' => 'active']);
        })->get();
        $providersCountOnlines = Provider::whereHas('service',function($service){
            $service->where(['status' => 'active']);
        })->count();
        //dd($providersCountOnline);


        $providersCountOffline = Provider::where(['status' => 'offline'])->count();
        $unavailableProvider = UserRequests::where('status','!=', 'COMPLETED')
            ->where('status','!=','CANCELLED')
            ->where('status','!=','SEARCHING')
            ->where('status','!=','SCHEDULED')->count();
            $userCountOnline = User::where('latitude' ,'!=', null)->where('longitude' ,'!=', null)->count();
        $providersCountOnline = $providersCountOnlines - $unavailableProvider;

        return view('admin.map.index',compact('providers', 'providersCountOnline', 'providersCountOffline','unavailableProvider','userCountOnline'));
    }

    /**
     * Map of all Users and Drivers.
     *
     * @return \Illuminate\Http\Response
     */
    public function map_ajax()
    {
        try {

            $Providers = Provider::where('latitude', '!=', 0)
                    ->where('longitude', '!=', 0)
                    ->with('service')
                    ->get();

            $Users = User::where('latitude', '!=', null)
                    ->where('longitude', '!=', null)
                    ->get();

            for ($i=0; $i < sizeof($Users); $i++) {
                $Users[$i]->status = 'user';
            }

            $All = $Users->merge($Providers);

            return $All;

        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function settings()
    {
        $admin=Admin::find(28);
        return view('admin.settings.application',['admin'=>$admin]);
    }

    public function dash_settings()
    {
        return view('admin.settings.dash_application');
    }

    public function condition_settings()
    {
        $conditions=ServiceConditions::all();
        return view('admin.condition.index',compact('conditions'));
    }
    public function condition_destroy(Request $request){

        try{
            ServiceConditions::findOrFail($_POST['id'])->delete();
            return back()->with('flash_success',trans('admin.deleteMessageSuccess'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', trans('admin.Condition Not Found'));
        }
    }


    public function condition_settings_store(Request $request)
    {
        $con=new ServiceConditions();
        $con->title= $request->title;
        $con->title_en= $request->title_en;
        $con->details= $request->details;
        $con->details_en= $request->details_en;
        $con->status= true;

        $con->save();

        return back()->with('flash_success',trans('admin.createMessageSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Provider $provider
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function settings_store(Request $request)
    {
        try{
            if(Setting::get('demo_mode', 0) == 1) {
                return back()->with('flash_error',trans('admin.Disabled for demo purposes! Please contact us at info@appoets.com'));
            }
            $this->validate($request,[
                    'site_title' => 'required',
                    'site_icon' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
                    'site_logo' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
                    'site_splash' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
                ]);

            if($request->hasFile('site_icon')) {
                //$site_icon = Helper::upload_picture($request->file('site_icon'));

                $picture = $request->site_icon;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."si";
                $picture->move('uploads/', $avatar_new_name);
                $x ='uploads/' . $avatar_new_name;

                Setting::set('site_icon', $x);
            }

            if($request->hasFile('site_logo')) {
                //$site_logo = Helper::upload_picture($request->file('site_logo'));

                $picture = $request->site_logo;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."sl";
                $picture->move('uploads/', $avatar_new_name);
                $x ='uploads/' . $avatar_new_name;

                Setting::set('site_logo', $x);
            }

            if($request->hasFile('site_splash')) {
                //$site_splash = Helper::upload_picture($request->file('site_splash'));

                $picture = $request->site_splash;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."sp";
                $picture->move('uploads/', $avatar_new_name);
                $x ='uploads/' . $avatar_new_name;

                Setting::set('site_splash', $x);
            }

            if($request->hasFile('gift_image')) {
                //$site_splash = Helper::upload_picture($request->file('site_splash'));

                $picture = $request->gift_image;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."g";
                $picture->move('uploads/gift/', $avatar_new_name);
                $x ='uploads/gift/' . $avatar_new_name;

                Setting::set('gift_image', $x);
            }

            Setting::set('site_title', $request->site_title);
            Setting::set('site_title_en', $request->site_title_en);
            Setting::set('app_status', $request->app_status);
            Setting::set('app_msg', $request->app_msg);
            Setting::set('store_link_android', $request->store_link_android);
            Setting::set('store_link_ios', $request->store_link_ios);
            Setting::set('provider_select_timeout', $request->provider_select_timeout);
            Setting::set('provider_search_radius', $request->provider_search_radius);
            Setting::set('user_search_radius', $request->user_search_radius);
            Setting::set('sos_number', $request->sos_number);
            Setting::set('contact_number', $request->contact_number);
            Setting::set('contact_email', $request->contact_email);

            $emailAdmin=Admin::find(28);
            $emailAdmin->email=$request->contact_email;
            $emailAdmin->email_password=$request->email_password;
            $emailAdmin->save();

            Setting::set('site_copyright', $request->site_copyright);
            Setting::set('social_login', $request->social_login);
            Setting::set('interval_time', $request->interval_time);
            Setting::set('search_title', $request->search_title);
            Setting::set('search_title_en', $request->search_title_en);
            Setting::set('address', $request->address);
            Setting::set('site_create', $request->site_create);
            Setting::set('site_create_en', $request->site_create_en);
            Setting::set('store_link_android_provider', $request->store_link_android_provider);


            Setting::set('user_andriod_version', $request->user_andriod_version);
            Setting::set('user_andriod_version_importance', $request->user_andriod_version_importance);

            Setting::set('provider_andriod_version', $request->provider_andriod_version);
            Setting::set('provider_andriod_version_importance', $request->provider_andriod_version_importance);
            Setting::set('gift_percentage', $request->gift_percentage);
            //Setting::set('gift_gift_image', $request->gift_image);


            //          Social Links
            Setting::set('facebook_link', $request->facebook_link);
            Setting::set('twitter_link', $request->twitter_link);
            Setting::set('youtube_link', $request->youtube_link);
            Setting::set('site_phone', $request->site_phone);
            Setting::set('whatsap_link', $request->whatsap_link);

            //          End Social Links
            Setting::save();
            //dd($emailAdmin->email);
            return back()->with('flash_success',trans('admin.editMessageSuccess'));
        }catch( \Exception  $e){
            dd($e);
            return back()->with('flash_error',trans('admin.editMessageError'));
        }
    }


    public function dash_settings_store(Request $request)
    {
        try{
            if(Setting::get('demo_mode', 0) == 1) {
                return back()->with('flash_error',trans('admin.Disabled for demo purposes! Please contact us at info@appoets.com'));
            }



            if($request->hasFile('First_site_photo')) {
                //$First_site_photo = Helper::upload_picture($request->file('First_site_photo'));

                $picture = $request->First_site_photo;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."fsp";
                $picture->move('uploads/', $avatar_new_name);
                $x ='uploads/' . $avatar_new_name;

                Setting::set('First_site_photo', $x);
            }

            Setting::set('text_first', $request->text_first);
            Setting::set('text_first_en', $request->text_first_en);

            if($request->hasFile('Second_site_photo')) {
                //$Second_site_photo = Helper::upload_picture($request->file('Second_site_photo'));

                $picture = $request->Second_site_photo;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."ssp";
                $picture->move('uploads/', $avatar_new_name);
                $x ='uploads/' . $avatar_new_name;

                Setting::set('Second_site_photo', $x);
            }

            Setting::set('about_title', $request->about_title);
            Setting::set('about_title_en', $request->about_title_en);
            Setting::set('about_small_title', $request->about_small_title);
            Setting::set('about_small_title_en', $request->about_small_title_en);



            //first
            if($request->hasFile('First_about_photo')) {
                //$First_about_photo = Helper::upload_picture($request->file('First_about_photo'));

                $picture = $request->First_about_photo;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."fap";
                $picture->move('uploads/', $avatar_new_name);
                $x ='uploads/' . $avatar_new_name;

                Setting::set('First_about_photo', $x);
            }
            Setting::set('first_name', $request->first_name);
            Setting::set('first_details', $request->first_details);
            Setting::set('first_name_en', $request->first_name_en);
            Setting::set('first_details_en', $request->first_details_en);

            //second
            if($request->hasFile('Second_about_photo')) {
                //$Second_about_photo = Helper::upload_picture($request->file('Second_about_photo'));

                $picture = $request->Second_about_photo;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."sap";
                $picture->move('uploads/', $avatar_new_name);
                $x ='uploads/' . $avatar_new_name;

                Setting::set('Second_about_photo', $x);
            }
            Setting::set('second_name', $request->second_name);
            Setting::set('second_details', $request->second_details);
            Setting::set('second_name_en', $request->second_name_en);
            Setting::set('second_details_en', $request->second_details_en);


            //third
            if($request->hasFile('Third_about_photo')) {
                //$Third_about_photo = Helper::upload_picture($request->file('Third_about_photo'));

                $picture = $request->Third_about_photo;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."tap";
                $picture->move('uploads/', $avatar_new_name);
                $x ='uploads/' . $avatar_new_name;

                Setting::set('Third_about_photo', $x);
            }
            Setting::set('third_name', $request->third_name);
            Setting::set('third_details', $request->third_details);
            Setting::set('third_name_en', $request->third_name_en);
            Setting::set('third_details_en', $request->third_details_en);




            if($request->hasFile('footer_photo')) {
                //$footer_photo = Helper::upload_picture($request->file('footer_photo'));

                $picture = $request->footer_photo;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."fp";
                $picture->move('uploads/', $avatar_new_name);
                $x ='uploads/' . $avatar_new_name;

                Setting::set('footer_photo', $x);
             }

             //       login user backgruond photo and title
            if($request->hasFile('user_backgruond_photo')) {
                //$user_backgruond_photo = Helper::upload_picture($request->file('user_backgruond_photo'));

                $picture = $request->user_backgruond_photo;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."ubp";
                $picture->move('uploads/', $avatar_new_name);
                $x ='uploads/' . $avatar_new_name;

                Setting::set('user_backgruond_photo', $x);
            }

            Setting::set('big_title_ar', $request->big_title_ar);
            Setting::set('big_title_en', $request->big_title_en);
            Setting::set('small_title_ar', $request->small_title_ar);
            Setting::set('small_title_en', $request->small_title_en);



            //       login provider backgruond photo and title
            if($request->hasFile('provider_backgruond_photo')) {
                //$provider_backgruond_photo = Helper::upload_picture($request->file('provider_backgruond_photo'));

                $picture = $request->provider_backgruond_photo;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."pbp";
                $picture->move('uploads/', $avatar_new_name);
                $x ='uploads/' . $avatar_new_name;

                Setting::set('provider_backgruond_photo', $x);
            }

            Setting::set('provider_big_title_ar', $request->provider_big_title_ar);
            Setting::set('provider_big_title_en', $request->provider_big_title_en);
            Setting::set('provider_small_title_ar', $request->provider_small_title_ar);
            Setting::set('provider_small_title_en', $request->provider_small_title_en);
            Setting::set('provider_ditals_ar', $request->provider_ditals_ar);
            Setting::set('provider_ditals_en', $request->provider_ditals_en);


            //       login admin backgruond photo a
            if($request->hasFile('admin_backgruond_photo')) {
                //$admin_backgruond_photo = Helper::upload_picture($request->file('admin_backgruond_photo'));

                $picture = $request->admin_backgruond_photo;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."abp";
                $picture->move('uploads/', $avatar_new_name);
                $x ='uploads/' . $avatar_new_name;

                Setting::set('admin_backgruond_photo',$x);
            }

             //       login company backgruond photo a
            if($request->hasFile('company_backgruond_photo')) {
                //$company_backgruond_photo = Helper::upload_picture($request->file('company_backgruond_photo'));

                $picture = $request->company_backgruond_photo;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."cbp";
                $picture->move('uploads/', $avatar_new_name);
                $x ='uploads/' . $avatar_new_name;

                Setting::set('company_backgruond_photo',$x);
            }

            Setting::save();

            return back()->with('flash_success',trans('admin.editMessageSuccess'));
        }catch( \Exception  $e){
            return back()->with('flash_error',trans('admin.editMessageError'));
        }
    }

    public function box_settings_store(Request $request)
    {
        $box=new Box();
        if($request->hasFile('box_photo')) {
            // $box_photo = Helper::upload_picture($request->file('box_photo'));
            // $box->photo=$box_photo;

            $picture = $request->file('box_photo');
            $code = rand(111111111, 999999999);
            $avatar_new_name=time().$code ."p";
            $picture->move('uploads/', $avatar_new_name);
            $x ='uploads/' . $avatar_new_name;
            $box->photo=$x;
        }
        $box->title= $request->box_title;
        $box->title_en= $request->box_title_en;
        $box->details= $request->box_details;
        $box->details_en= $request->box_details_en;
        $box->link= $request->box_link;

        $box->save();

        return back()->with('flash_success',trans('admin.createMessageSuccess'));
    }

    public function box_delete(Request $request)
    {
        Box::findOrFail($_POST['box_id'])->delete();

        return back()->with('flash_success',trans('admin.deleteMessageSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function settings_payment()
    {
        return view('admin.payment.settings');
    }

    /**
     * Save payment related settings.
     *
     * @param \App\Provider $provider
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function settings_payment_store(Request $request)
    {
        // dd($request->all());
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error',trans('admin.Disabled for demo purposes! Please contact us at info@appoets.com'));
        }

        $this->validate($request, [
                'CARD' => 'in:on',
                'CASH' => 'in:on',
                'stripe_secret_key' => 'required_if:CARD,on|max:255',
                'stripe_publishable_key' => 'required_if:CARD,on|max:255',
                'daily_target' => 'required|integer|min:0',
                'tax_percentage' => 'required|numeric|min:0|max:100',
                'surge_percentage' => 'required|numeric|min:0|max:100',
                'commission_percentage' => 'required|numeric|min:0|max:100',
                // 'sub_com' => 'required|numeric',
                'surge_trigger' => 'required|integer|min:0',
                'currency' => 'required'
            ]);

        Setting::set('CARD', $request->has('CARD') ? 1 : 0 );
        Setting::set('CASH', $request->has('CASH') ? 1 : 0 );
        Setting::set('stripe_secret_key', $request->stripe_secret_key);
        Setting::set('stripe_publishable_key', $request->stripe_publishable_key);
        Setting::set('daily_target', $request->daily_target);
        Setting::set('tax_percentage', $request->tax_percentage);
        Setting::set('surge_percentage', $request->surge_percentage);
        Setting::set('commission_percentage', $request->commission_percentage);
        // Setting::set('sub_com', $request->sub_com);
        Setting::set('pay_for_ad', $request->pay_for_ad);
        Setting::set('surge_trigger', $request->surge_trigger);
        Setting::set('currency', $request->currency);
        Setting::set('booking_prefix', $request->booking_prefix);
        Setting::save();

        return back()->with('flash_success',trans('admin.editMessageSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return view('admin.account.profile');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function profile_update(Request $request)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', trans('admin.Disabled for demo purposes! Please contact us at info@appoets.com'));
        }

        $this->validate($request,[
            'name' => 'required|max:255',
            'email' => 'email',
            'picture' => 'mimes:jpeg,jpg,bmp,png',
        ]);

        try{
            $admin = Auth::guard('admin')->user();
            $admin->name = $request->name;
            $admin->email = $request->email;
            // if (!empty($request->picture)) $admin->picture = Helper::upload_picture($request->file('picture'));
            if (!empty($request->picture)){
                $picture = $request->picture;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."pp";
                $picture->move('uploads/admin/', $avatar_new_name);
                $admin->picture ='uploads/admin/' . $avatar_new_name;
            }


            $admin->save();

            return redirect()->back()->with('flash_success',trans('admin.editMessageSuccess'));
        }

        catch (Exception $e) {
             return back()->with('flash_error',trans('admin.Something Went Wrong'));
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function password()
    {
        return view('admin.account.change-password');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password_update(Request $request)
    {
        if(Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error',trans('admin.Disabled for demo purposes! Please contact us at info@appoets.com'));
        }

        $this->validate($request,[
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        try {

           $Admin = Admin::find(Auth::guard('admin')->user()->id);

            if(password_verify($request->old_password, $Admin->password))
            {
                $Admin->password = bcrypt($request->password);
                $Admin->save();

                return redirect()->back()->with('flash_success',trans('admin.editMessageSuccess'));
            }
        } catch (Exception $e) {
             return back()->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function payment()
    {
        try {
             $payments = UserRequests::where('paid', 1)
                    ->has('user')
                    ->has('provider')
                    ->has('payment')
                    ->orderBy('user_requests.created_at','desc')
                    ->get();

            return view('admin.payment.payment-history', compact('payments'));
        } catch (Exception $e) {
             return back()->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function help()
    {
        try {
            $str = file_get_contents('http://appoets.com/help.json');
            $Data = json_decode($str, true);
            return view('admin.help', compact('Data'));
        } catch (Exception $e) {
             return back()->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }

    /**
     * User Rating.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function user_review()
    {
        try {
            $Reviews = UserRequestRating::where('user_id', '!=', 0)->get(); // ->has('user', 'provider')
            return view('admin.review.user_review',compact('Reviews'));
        } catch(Exception $e) {
            return redirect()->route('admin.setting')->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }

    /**
     * Provider Rating.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function provider_review()
    {
        try {
            $Reviews = UserRequestRating::where('provider_id','!=',0)->with('user','provider')->get();
            return view('admin.review.provider_review',compact('Reviews'));
        } catch(Exception $e) {
            return redirect()->route('admin.setting')->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProviderService
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destory_provider_service($id){
        try {
            ProviderService::find($id)->delete();
            return back()->with('flash_success', trans('admin.deleteMessageSuccess'));
        } catch (Exception $e) {
             return back()->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }

    /**
     * Testing page for push notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function push_index()
    {
        $data = PushNotification::app('IOSUser')
            ->to('163e4c0ca9fe084aabeb89372cf3f664790ffc660c8b97260004478aec61212c')
            ->send('Hello World, i`m a push message');
        dd($data);

        $data = PushNotification::app('IOSProvider')
            ->to('a9b9a16c5984afc0ea5b681cc51ada13fc5ce9a8c895d14751de1a2dba7994e7')
            ->send('Hello World, i`m a push message');
        dd($data);
    }

    /**
     * Testing page for push notifications.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function push_store(Request $request,$id)
    {
        try {
            ProviderService::find($id)->delete();
            return back()->with('flash_success', 'Service deleted successfully');
        } catch (Exception $e) {
             return back()->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }

    /**
     * privacy.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function privacy(){
        return view('admin.pages.static')
            ->with('title',"Privacy Page")
            ->with('page', "privacy");
    }

    /**
     * pages.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
//    public function pages(Request $request){
//        $this->validate($request, [
//                'page' => 'required|in:page_privacy',
//                'content' => 'required',
//            ]);
//
//        Setting::set($request->page, $request->content);
//        Setting::save();
//
//        return back()->with('flash_success', 'Content Updated!');
//    }

    /**
     * account statements.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function statement($type = 'individual'){

        try{

            $page = 'Ride Statement';

            if($type == 'individual'){
                $page = 'Provider Ride Statement';
            }elseif($type == 'today'){
                $page = 'Today Statement - '. date('d M Y');
            }elseif($type == 'monthly'){
                $page = 'This Month Statement - '. date('F');
            }elseif($type == 'yearly'){
                $page = 'This Year Statement - '. date('Y');
            }

            $rides = UserRequests::with('payment')->orderBy('id','desc');
            $cancel_rides = UserRequests::where('status','CANCELLED');
            $revenue = UserRequestPayment::select(\DB::raw(
                           'SUM(ROUND(fixed) + ROUND(distance)) as overall, SUM(ROUND(commision)) as commission'
                       ));

            if($type == 'today'){

                $rides->where('created_at', '>=', Carbon::today());
                $cancel_rides->where('created_at', '>=', Carbon::today());
                $revenue->where('created_at', '>=', Carbon::today());

            }elseif($type == 'monthly'){

                $rides->where('created_at', '>=', Carbon::now()->month);
                $cancel_rides->where('created_at', '>=', Carbon::now()->month);
                $revenue->where('created_at', '>=', Carbon::now()->month);

            }elseif($type == 'yearly'){

                $rides->where('created_at', '>=', Carbon::now()->year);
                $cancel_rides->where('created_at', '>=', Carbon::now()->year);
                $revenue->where('created_at', '>=', Carbon::now()->year);

            }

            $rides = $rides->get();
            $cancel_rides = $cancel_rides->count();
            $revenue = $revenue->get();

            return view('admin.providers.statement', compact('rides','cancel_rides','revenue'))
                    ->with('page',$page);

        } catch (Exception $e) {
            return back()->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }


    /**
     * account statements today.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_today(){
        return $this->statement('today');
    }

    /**
     * account statements monthly.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_monthly(){
        return $this->statement('monthly');
    }

     /**
     * account statements monthly.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function statement_yearly(){
        return $this->statement('yearly');
    }


    /**
     * account statements.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function statement_provider(){

        try{

            $Providers = Provider::all();

            foreach($Providers as $index => $Provider){

                $Rides = UserRequests::where('provider_id',$Provider->id)
                            ->where('status','<>','CANCELLED')
                            ->get()->pluck('id');

                $Providers[$index]->rides_count = $Rides->count();

                $Providers[$index]->payment = UserRequestPayment::whereIn('request_id', $Rides)
                                ->select(\DB::raw(
                                   'SUM(ROUND(fixed) + ROUND(distance)) as overall, SUM(ROUND(commision)) as commission'
                                ))->get();
            }

            return view('admin.providers.provider-statement', compact('Providers'))->with('page','Providers Statement');

        } catch (Exception $e) {
            return back()->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Provider  $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function translation()
    {
        try{
            return view('admin.translation');
        }
        catch (Exception $e) {
             return back()->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }


    public function condition_ChangeStatus(Request $request){
        $con = ServiceConditions::all();
        $conn = ServiceConditions::findOrFail($_POST['con_id']);
        if($request->ajax()) {
            $conn->status=$_POST['status'];
            $conn->save();
            return $conn;
        } else {
            return view('admin.condition.index', compact('con'));
        }
    }

    public function Change_Status(Request $request){
        $Model = '\App\\'.$request->model;
        $Model::where('id',$request->id)->update(['status' => ($Model::where('id',$request->id)->first()->status -1)*-1]);
    }
}
