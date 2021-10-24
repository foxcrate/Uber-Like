<?php

namespace App\Http\Controllers\Resource;

use App\Car;
use App\CarClass;
use App\CarModel;
use App\EmailProvider;
use App\Fleet;
use App\Admin;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProviderResources\ProfileController;
use App\Mail\AlBazAcceptedData;
use App\Mail\alBazApprovedEmail;
use App\Mail\EilbazCreatedProvider;
use App\Mail\EilbazProviderNotImage;
use App\Mail\FleetConfirmationCar;
use App\Mail\ProviderAccountConfirmation;
use App\Mail\ProviderConfirmationCar;
use App\MobileProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Setting;
use Storage;

use App\Provider;
use App\ProviderService;
use App\UserRequestPayment;
use App\UserRequests;
use App\Helpers\Helper;
use App\Revenue;

class ProviderResource extends Controller
{
//    public function commissionRequest($id)
//    {
//        $provider = Provider::find($id);
//        $test = UserRequests::where('provider_id', $provider->id)->get();
//        return view('admin.providers.commissionRequest', compact('providers','test'));
//
////        foreach ($test->load('payment') as $items) {
////            dd($items->payment->update(['commision' => '5']));
////        }
//    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        //dd("aloo");
        ProfileController::checkData();
        $AllProviders = Provider::with('service', 'accepted', 'cancelled');
        $providers = $AllProviders->orderBy('created_at', 'desc')->get();

        return view('admin.providers.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @return Provider
     */
    public function create()
    {
        //dd("aloo");
        ProfileController::checkData();
        $fleet = DB::table('fleets')->get();
        $governorates = DB::table('governorates')->get();
        $car_models = DB::table('car_models')->get();
        return view('admin.providers.create', compact('fleet', 'car_models', 'governorates'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $Provider
     * @param $providerservice
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        if (Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', 'Disabled for demo purposes! Please contact us at info@appoets.com');
        }

        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|unique:providers,email|email|max:255',
            'identity_number'=>'required|min:14|max:14',
            'phone_number' => 'required|min:11|max:11|regex:/(01)[0-9]{9}/',
            'country_code' => 'required',
            'avatar' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
            'password' => 'required|min:6|confirmed',
            'fleet' => 'required',
            'car_type' => 'required',
            'governorate_id' => 'required|exists:governorates,id',
        ]);

        try {
            $providers = MobileProvider::where('mobile', '=', $request['country_code'] . $request['phone_number'])->first();

            if ($providers != null) {
                flash()->error(trans('user.The phone is already in use'));
                return redirect()->back();
            }
            $provider = $request->all();
            $provider['password'] = bcrypt($request->password);
            $provider['first_name']= base64_encode($request->first_name);
            $provider['last_name']= base64_encode($request->last_name);
            $provider['fleet'] = $request->fleet;

            $provider = Provider::create($provider);
            $provider->mobile = $request['country_code'] . $request['phone_number'];
            $provider->identity_number=$request->identity_number;
            $provider->save();
            $this->saveProviderImages($provider, $request);
            //$provider->serviceTypes()->attach($request->service_type_id);

            $emailProvider = EmailProvider::create([
                'provider_id' => $provider->id,
                'email' => $request->email,
            ]);

            $mobileProvider = MobileProvider::create([
                'provider_id' => $provider->id,
                'mobile' => $provider->mobile,
            ]);
            if ($provider->car_type == 'true') {
                return redirect(route('admin.carTypeTrue', $provider->id))->with('flash_success', trans('admin.createMessageSuccess'));
            } elseif ($provider->car_type == 'false') {
                return redirect(route('admin.carTypeFalse', $provider->id))->with('flash_success', trans('admin.createMessageSuccess'));
            }
        } catch (Exception $e) {
            return back()->with('flash_error', trans('admin.Provider Not Found'));
        }
    }


    public function carTypeTrue($id)
    {
        $provider = Provider::findOrFail($id);
        return view('admin.providers.carTypeTrue', compact('provider'));
    }

    public function postCarTypeTrue(Request $request, $id)
    {
        $this->validate($request, [
            'car_code' => 'required|exists:cars,car_code',
        ]);

        $provider = Provider::findOrFail($id);
        $car = Car::where('car_code', $request->car_code)->with('providers')->first();
        $provider_car = Provider::where('car_id', $car->id)->first();
        $fleet = Fleet::findOrFail($car->fleet_id);
        if (count($car) > 0) {
            $provider_service = ProviderService::create([
                'provider_id' => $provider->id,
                'service_type_id' => $car->carModel->service_id,
                'status' => 'offline',
            ]);

            $car->providers()->attach($provider->id, ['status' => 'not_active']);
            $ids = $provider->email;
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
                //     ->send(new EilbazCreatedProvider($ids));

                $admin=Admin::find(28);
                config(['mail.username' => $admin->email]);
                config(['mail.password'=>$admin->email_password]);
                //dd( $admin->email, $admin->email_password);

                Mail::to($provider->email)
                ->bcc('ailbaza156@gmail.com')
                //->bcc(Admin::find(28)->email)
                ->send(new EilbazCreatedProvider($ids));

                return redirect(route('admin.provider.index'))->with('flash_success', trans('admin.createMessageSuccess'));
            } else {

                if (count($provider_car) != null) {
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
                //     ->send(new EilbazCreatedProvider($ids));

                    $admin=Admin::find(28);
                    config(['mail.username' => $admin->email]);
                    config(['mail.password'=>$admin->email_password]);
                    //dd( $admin->email, $admin->email_password);

                    Mail::to($provider->email)
                    ->bcc('ailbaza156@gmail.com')
                    //->bcc(Admin::find(28)->email)
                    ->send(new EilbazCreatedProvider($ids));

                return redirect(route('admin.provider.index'))->with('flash_success', trans('admin.createMessageSuccess'));
            }
        } else {
            return back()->with('flash_error', trans('admin.Car Code Not Found'));
        }
    }

    public function carTypeFalse($id)
    {
        $provider = Provider::findOrFail($id);
        $car_models = DB::table('car_models')->get();
        return view('admin.providers.carTypeFalse', compact('provider', 'car_models'));
    }

    public function postCarTypeFalse(Request $request, $id)
    {
        $this->validate($request, [
            'service_type_id' => 'required',
            'car_number1' => array(
                'required',
                'max:4',
                'min:2'
            ),
            'car_number2' => array(
                'required',
                'regex:/[أ-ي]/',
                'max:4',
                'min:2'
            ),
            //'car_number' => 'required|max:8|regex:/[أ-ي]/',
            'color' => 'required',
        ]);
        $provider = Provider::findOrFail($id);
        $providerService = CarModel::find($request->service_type_id);

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
            'car_number' => $request->car_number1 . $request->car_number2,
            'car_model_id' => $providerService->id,
            'color' => $request->color,
        ]);
        $cars->providers()->attach($provider->id);

        $update = $provider->update([
            'car_id' => $cars->id,
        ]);

        if ($request->hasFile('car_front')) {
            $car_front = $request->car_front;
            $code = rand(111111111, 999999999);
            $car_front_new_name = time() . $code."cf";
            $car_front->move('uploads/car', $car_front_new_name);

            $cars->car_front = 'uploads/car/' . $car_front_new_name;
            $cars->save();
        }

        if ($request->hasFile('car_back')) {
            $car_back = $request->car_back;
            $code = rand(111111111, 999999999);
            $car_back_new_name = time() . $code ."cb";
            $car_back->move('uploads/car', $car_back_new_name);

            $cars->car_back = 'uploads/car/' . $car_back_new_name;
            $cars->save();
        }

        if ($request->hasFile('car_left')) {
            $car_left = $request->car_left;
            $code = rand(111111111, 999999999);
            $car_left_new_name = time() . $code."cl";
            $car_left->move('uploads/car', $car_left_new_name);

            $cars->car_left = 'uploads/car/' . $car_left_new_name;
            $cars->save();
        }

        if ($request->hasFile('car_right')) {
            $car_right = $request->car_right;
            $code = rand(111111111, 999999999);
            $car_right_new_name = time() . $code ."cr";
            $car_right->move('uploads/car', $car_right_new_name);

            $cars->car_right = 'uploads/car/' . $car_right_new_name;
            $cars->save();
        }

        if ($request->hasFile('car_licence_front')) {
            $car_licence_front = $request->car_licence_front;
            $code = rand(111111111, 999999999);
            $car_licence_front_new_name = time() . $code ."clf";
            $car_licence_front->move('uploads/car', $car_licence_front_new_name);

            $cars->car_licence_front = 'uploads/car/' . $car_licence_front_new_name;
            $cars->save();
        }

        if ($request->hasFile('car_licence_back')) {
            $car_licence_back = $request->car_licence_back;
            $code = rand(111111111, 999999999);
            $car_licence_back_new_name = time() . $code."clb";
            $car_licence_back->move('uploads/car', $car_licence_back_new_name);

            $cars->car_licence_back = 'uploads/car/' . $car_licence_back_new_name;
            $cars->save();
        }

        $code = rand(111111, 999999);
        $ids = $provider->id;
        $update = $provider->update([
            'otp' => $code,
        ]);
        if ($update) {

            // Mail::to($provider->email)
            //     ->bcc(Admin::find(28)->email)
            //     ->send(new EilbazCreatedProvider($code, $ids));

                $admin=Admin::find(28);
                config(['mail.username' => $admin->email]);
                config(['mail.password'=>$admin->email_password]);
                //dd( $admin->email, $admin->email_password);

                Mail::to($provider->email)
                ->bcc('ailbaza156@gmail.com')
                //->bcc(Admin::find(28)->email)
                ->send(new EilbazCreatedProvider($code, $ids));

            return redirect(route('admin.provider.index'))->with('flash_success', trans('admin.createMessageSuccess'));
        } else {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }
    }


    private function saveProviderImages($Provider, $request)
    {
        $pro = Provider::find($Provider->id);

        // if (!empty($request->avatar)) $pro->avatar = Helper::upload_picture($request->file('avatar'));
        if (!empty($request->avatar)){
            $picture = $request->avatar;
            $code = rand(111111111, 999999999);
            $avatar_new_name=time().$code ."pp";
            $picture->move('uploads/provider/', $avatar_new_name);
            $pro->avatar ='uploads/provider/' . $avatar_new_name;
        }
        //if (!empty($request->driver_licence_front)) $pro->driver_licence_front = Helper::upload_picture($request->file('driver_licence_front'));
        if (!empty($request->driver_licence_front)){
            $picture = $request->driver_licence_front;
            $code = rand(111111111, 999999999);
            $avatar_new_name=time().$code ."dlf";
            $picture->move('uploads/provider/', $avatar_new_name);
            $pro->driver_licence_front ='uploads/provider/' . $avatar_new_name;
        }
        // if (!empty($request->driver_licence_back)) $pro->driver_licence_back = Helper::upload_picture($request->file('driver_licence_back'));
        if (!empty($request->driver_licence_back)){
            $picture = $request->driver_licence_back;
            $code = rand(111111111, 999999999);
            $avatar_new_name=time().$code ."dlb";
            $picture->move('uploads/provider/', $avatar_new_name);
            $pro->driver_licence_back ='uploads/provider/' . $avatar_new_name;
        }
        // if (!empty($request->identity_front)) $pro->identity_front = Helper::upload_picture($request->file('identity_front'));
        if (!empty($request->identity_front)){
            $picture = $request->identity_front;
            $code = rand(111111111, 999999999);
            $avatar_new_name=time().$code ."if";
            $picture->move('uploads/provider/', $avatar_new_name);
            $pro->identity_front ='uploads/provider/' . $avatar_new_name;
        }
        // if (!empty($request->identity_back)) $pro->identity_back = Helper::upload_picture($request->file('identity_back'));
        if (!empty($request->identity_back)){
            $picture = $request->identity_back;
            $code = rand(111111111, 999999999);
            $avatar_new_name=time().$code ."ib";
            $picture->move('uploads/provider/', $avatar_new_name);
            $pro->identity_back ='uploads/provider/' . $avatar_new_name;
        }
        // if (!empty($request->criminal_feat)) $pro->criminal_feat = Helper::upload_picture($request->file('criminal_feat'));
        if (!empty($request->criminal_feat)){
            $picture = $request->criminal_feat;
            $code = rand(111111111, 999999999);
            $avatar_new_name=time().$code ."cf";
            $picture->move('uploads/provider/', $avatar_new_name);
            $pro->criminal_feat ='uploads/provider/' . $avatar_new_name;
        }
        // if (!empty($request->drug_analysis_licence)) $pro->drug_analysis_licence = Helper::upload_picture($request->file('drug_analysis_licence'));
        if (!empty($request->drug_analysis_licence)){
            $picture = $request->drug_analysis_licence;
            $code = rand(111111111, 999999999);
            $avatar_new_name=time().$code ."dal";
            $picture->move('uploads/provider/', $avatar_new_name);
            $pro->drug_analysis_licence ='uploads/provider/' . $avatar_new_name;
        }

        return $pro->save();
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Provider $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $provider = Provider::findOrFail($id);
            return view('admin.providers.provider-details', compact('provider'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Provider $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $provider = Provider::findOrFail($id);
            $car_models = DB::table('car_models')->get();
            $governorates = DB::table('governorates')->get();
            $fleet = DB::table('fleets')->get();
            return view('admin.providers.edit', compact('provider', 'car_models', 'fleet', 'governorates'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Provider $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required',
            'identity_number'=>'required|min:14|max:14',
            'mobile' => 'required',
            'avatar' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
            'fleet' => 'nullable',
            'governorate_id' => 'required|exists:governorates,id',
        ]);

        try {

            $provider = Provider::findOrFail($id);

            if ($provider->mobile != $request->mobile) {
                $providersMobile = MobileProvider::where('mobile', '=', $request['mobile'])->first();
                if ($providersMobile != null) {
                    flash()->error(trans('user.The phone is already in use'));
                    return redirect()->back();
                }
            }

            if ($provider->email != $request->email) {
                $emailProviders = EmailProvider::where('email', '=', $request['email'])->first();
                if ($emailProviders != null) {
                    flash()->error(trans('user.The email is already in use'));
                    return redirect()->back();
                }
            }

            if ($request->email != $provider->email) {
                $emailProvider = EmailProvider::create([
                    'provider_id' => $provider->id,
                    'email' => $request->email,
                ]);
            }

            if ($request->mobile != $provider->mobile) {
                $mobileProvider = MobileProvider::create([
                    'provider_id' => $provider->id,
                    'mobile' => $request->mobile,
                ]);
            }

            $provider->first_name = base64_encode($request->first_name);
            $provider->last_name = base64_encode($request->last_name);
            $provider->mobile = $request->mobile;
            $provider->fleet = $request->fleet;
            $provider->email = $request->email;
            $provider->governorate_id = $request->governorate_id;
            $provider->identity_number=$request->identity_number;
            if (!empty($request->avatar)){
                $picture = $request->avatar;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."pp";
                $picture->move('uploads/provider/', $avatar_new_name);
                $provider->avatar ='uploads/provider/' . $avatar_new_name;
            }

            $provider->save();

            // if ($request->email != $provider->email) {
            //     $emailProvider = EmailProvider::create([
            //         'provider_id' => $provider->id,
            //         'email' => $provider->email,
            //     ]);
            // }

            // if ($request->mobile != $provider->mobile) {
            //     $mobileProvider = MobileProvider::create([
            //         'provider_id' => $provider->id,
            //         'mobile' => $provider->mobile,
            //     ]);
            // }
            //$this->saveProviderImages($provider, $request);

            return redirect()->route('admin.provider.index')->with('flash_success', trans('admin.editMessageSuccess'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', trans('admin.Provider Not Found'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Provider $provider
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', trans('admin.Disabled for demo purposes! Please contact us at info@appoets.com'));
        }

        try {
            $revrnue = Revenue::where('provider_id', $id)->get()->first();
             $revrnueacuont = Revenue::where('provider_id', $id)->count();
            if ($revrnueacuont == 0 ){
                Provider::find($id)->delete();
            return back()->with('flash_success', trans('admin.deleteMessageSuccess'));
            }elseif ($revrnueacuont == 1 && $revrnue->status == 'time_finish') {
                Revenue::where('provider_id', $id)->delete();
                Provider::find($id)->delete();
            return back()->with('flash_success', trans('admin.deleteMessageSuccess'));
            }elseif ($revrnueacuont == 1 && $revrnue->status == 'active'){
                return back()->with('flash_error', trans('admin.Provider Not Found2'));

            }

        } catch (Exception $e) {
            return back()->with('flash_error', trans('admin.Provider Not Found'));
        }
    }

    public function send_message(Request $request, $id)
    {
        $provider = Provider::findOrFail($id);
        return view('admin.providers.sendMessage', compact('provider'));
    }

    public function post_send_message(Request $request , $id)
    {

        $this->validate($request, [
            'message' => 'required',
            'avatar_status' => 'nullable',
            'driver_licence_front_status' => 'nullable',
            'driver_licence_back_status' => 'nullable',
            'identity_front_status' => 'nullable',
            'identity_back_status' => 'nullable',
            'criminal_feat_status' => 'nullable',
            'drug_analysis_licence_status' => 'nullable',
        ]);
        try{


            $provider = Provider::whereId($id)->get()->first();
            $message = $request->message;
            $update=null;
            if ($provider) {
                if ($request->avatar_status == 1) {
                    $update = $provider->update(['avatar' => null]);
                }
                if ($request->driver_licence_front_status == 1) {
                    $update = $provider->update(['driver_licence_front' => null]);
                }
                if ($request->driver_licence_back_status == 1) {
                    $update = $provider->update(['driver_licence_back' => null]);
                }
                if ($request->identity_front_status == 1) {
                    $update = $provider->update(['identity_front' => null]);
                }
                if ($request->identity_back_status == 1) {
                    $update = $provider->update(['identity_back' => null]);
                }
                if ($request->criminal_feat_status == 1) {
                    $update = $provider->update(['criminal_feat' => null]);
                }
                if ($request->drug_analysis_licence_status == 1) {
                    $update = $provider->update(['drug_analysis_licence' => null]);
                }

                if ($update != null ) {

                    // Mail::to($provider->email)
                    //     ->bcc(Admin::find(28)->email)
                    //     ->send(new EilbazProviderNotImage($message));

                    $admin=Admin::find(28);
                    config(['mail.username' => $admin->email]);
                    config(['mail.password'=>$admin->email_password]);
                    //dd( $admin->email, $admin->email_password);

                    Mail::to($provider->email)
                    ->bcc('ailbaza156@gmail.com')
                    //->bcc(Admin::find(28)->email)
                    ->send(new EilbazProviderNotImage($message));

                    return back()->with('flash_success', trans('admin.edit Message Success And the message was sent to the e-mail'));
                } else {
                    // Mail::to($provider->email)
                    //     ->bcc(Admin::find(28)->email)
                    //     ->send(new EilbazProviderNotImage($message));

                    $admin=Admin::find(28);
                    config(['mail.username' => $admin->email]);
                    config(['mail.password'=>$admin->email_password]);
                    //dd( $admin->email, $admin->email_password);

                    Mail::to($provider->email)
                    ->bcc('ailbaza156@gmail.com')
                    //->bcc(Admin::find(28)->email)
                    ->send(new EilbazProviderNotImage($message));

                    return back()->with('flash_success', trans('admin.edit Message Success And the message was sent to the e-mail'));
                }
            } else {
                return back()->with('flash_error', trans('admin.Provider Not Found'));
            }
        }catch(Exception $e) {
            return $e;
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Provider $provider
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */

    public function approve($id)
    {
        try {
            $Provider = Provider::findOrFail($id);

            $Provider->update(['status' => 'approved']);
            // Mail::to($Provider->email)
            //     ->bcc(Admin::find(28)->email)
            //     ->send(new alBazApprovedEmail());

                $admin=Admin::find(28);
                config(['mail.username' => $admin->email]);
                config(['mail.password'=>$admin->email_password]);
                //dd( $admin->email, $admin->email_password);

                Mail::to($Provider->email)
                ->bcc('ailbaza156@gmail.com')
                //->bcc(Admin::find(28)->email)
                ->send(new alBazApprovedEmail());

            return back()->with('flash_success', trans('admin.edit Message Success And the message was sent to the e-mail'));

        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Provider $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disapprove($id)
    {

        $Provider = Provider::findOrFail($id);
        $Provider->status = 'onboarding';
        if ($Provider->save()) {
//            $Provider->service->update(['status' => 'offline']);
            return back()->with('flash_success', trans('admin.editMessageSuccess'));
        } else {
            return back()->with('flash_error', trans('admin.editMessageError'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Provider $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function banned($id)
    {
        $Provider = Provider::findOrFail($id);
        $Provider->status = 'banned';
        if ($Provider->save()) {
//            $Provider->service->update(['status' => 'offline']);
            return back()->with('flash_success', trans('admin.editMessageSuccess'));
        } else {
            return back()->with('flash_error', trans('admin.editMessageError'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Provider $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function request($id)
    {

        try {
            $requests = UserRequests::where('user_requests.provider_id', $id)
                ->RequestHistory()
                ->get();
//  ->whereMonth('created_at', Carbon::now()->month)->whereDay('created_at', Carbon::now()->day)

//            $provider = Provider::find($id);
//            $requests = UserRequests::where('provider_id', $provider->id)->get();

            return view('admin.request.index', compact('requests'));
        } catch (Exception $e) {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }
    }

    /**
     * account statements.
     *
     * @param \App\Provider $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function statement($id)
    {

        try {

            $requests = UserRequests::where('provider_id', $id)
                ->where('status', 'COMPLETED')
                ->with('payment')
                ->get();

            $rides = UserRequests::where('provider_id', $id)->with('payment')->orderBy('id', 'desc')->paginate(10);
            $cancel_rides = UserRequests::where('status', 'CANCELLED')->where('provider_id', $id)->count();
            $Provider = Provider::find($id);
            $revenue = UserRequestPayment::whereHas('request', function ($query) use ($id) {
                $query->where('provider_id', $id);
            })->select(\DB::raw(
                'SUM(ROUND(fixed) + ROUND(distance)) as overall, SUM(ROUND(commision)) as commission'
            ))->get();


            $Joined = $Provider->created_at ? '- Joined ' . $Provider->created_at->diffForHumans() : '';

            return view('admin.providers.statement', compact('rides', 'cancel_rides', 'revenue'))
                ->with('page', base64_decode($Provider->first_name) . "'s Overall Statement " . $Joined);

        } catch (Exception $e) {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }
    }

    public function Accountstatement($id)
    {

        try {

            $requests = UserRequests::where('provider_id', $id)
                ->where('status', 'COMPLETED')
                ->with('payment')
                ->get();

            $rides = UserRequests::where('provider_id', $id)->with('payment')->orderBy('id', 'desc')->paginate(10);
            $cancel_rides = UserRequests::where('status', 'CANCELLED')->where('provider_id', $id)->count();
            $Provider = Provider::find($id);
            $revenue = UserRequestPayment::whereHas('request', function ($query) use ($id) {
                $query->where('provider_id', $id);
            })->select(\DB::raw(
                'SUM(ROUND(fixed) + ROUND(distance)) as overall, SUM(ROUND(commision)) as commission'
            ))->get();


            $Joined = $Provider->created_at ? '- Joined ' . $Provider->created_at->diffForHumans() : '';

            return view('account.providers.statement', compact('rides', 'cancel_rides', 'revenue'))
                ->with('page', base64_decode($Provider->first_name) . "'s Overall Statement " . $Joined);

        } catch (Exception $e) {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }
    }

    public function wallet_update(Request $request)
    {
        try {
            $provider = Provider::where(['id' => $request->user_id])->first();
//            dd($request->user_id);
            $total_cash = $provider->wallet_balance + $request->cash;
            $provider->wallet_balance = $total_cash;
            $provider->save();
            return redirect()->back();
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    public function trashed()
    {
        $provider = Provider::onlyTrashed()->get();

        return view('admin.providers.softDeleted')->with('providers', $provider);
    }

    public function hdelete($id)
    {
        $worker = Provider::withTrashed()->where('id', $id)->first();
        $worker->forceDelete();
        return redirect()->back();
    }

    public function restore($id)
    {
        $worker = Provider::withTrashed()->where('id', $id)->first();
        $worker->restore();
        return redirect()->route('admin.provider.index');
    }
}
