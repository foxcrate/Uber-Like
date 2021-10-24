<?php

namespace App\Http\Controllers\ProviderAuth;

use anlutro\LaravelSettings\ServiceProvider;
use App\Car;
use App\CarModel;
use App\CarProvider;
use App\CompanySubscription;
use App\Document;
use App\EmailProvider;
use App\Fleet;
use App\Helpers\Helper;
use App\Http\Controllers\ProviderResources\ProfileController;
use App\Mail\alBazAccountConfirmation;
use App\Http\Controllers\Resource\ProviderResource;
use App\Mail\EilbazResetPasswordProvider;
use App\Mail\FleetConfirmationCar;
use App\Mail\ProviderAccountConfirmation;
use App\Mail\ProviderConfirmationCar;
use App\MobileProvider;
use App\ProviderProfile;
use App\Promocode;
use App\Provider;
use App\Otp;
use App\ProviderService;
use App\Revenue;
use App\User;
use App\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class ProviderController extends Controller
{
    //

    public function getLogin()
    {
        ProfileController::checkData();
        return view('provider.auth.login');
    }

    public function login(Request $request)
    {

        //dd(auth()->guard('provider'));

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        $rememberme = request('rememberme') == 1 ? true : false;
        if (auth()->guard('provider')->attempt(['email' => request('email'), 'password' => request('password')], $rememberme)) {
            //dd();
            auth('provider')->user()->id_url = str_random(100);
            auth('provider')->user()->save();

            if (auth('provider')->user()->otp != 0) {
                flash()->error(trans('user.The account must be confirmed first') . '<a href="' . route('provider.auth.accountConfirmation', [auth('provider')->user()->id,auth('provider')->user()->id_url]) . '" class="btn btn-xs btn-primary" style="margin-left: 20px;">' . trans('user.Account Confirmation') . '</a>');
                auth()->guard('provider')->logout();
                return redirect()->route('provider.login');
            }
            auth()->guard('provider')->logoutOtherDevices($request->password);
            //|| auth('provider')->user()->criminal_feat == null || auth('provider')->user()->drug_analysis_licence == null
            if (auth('provider')->user()->avatar == null || auth('provider')->user()->driver_licence_back == null || auth('provider')->user()->driver_licence_front == null || auth('provider')->user()->identity_front == null || auth('provider')->user()->identity_back == null ) {
                $id = auth('provider')->user()->id;
                $id_url = auth('provider')->user()->id_url;
                flash()->error(trans('user.The photos must be re-uploaded again'));
                auth()->guard('provider')->logout();
                return redirect()->route('provider.image.upload', [$id,$id_url]);
            }
            if (count(auth('provider')->user()->cars) == 0) {
                $id = auth('provider')->user()->id;
                $id_url = auth('provider')->user()->id_url;
                if (auth('provider')->user()->car_type == 'true') {
                    flash()->error(trans('user.The account must be confirmed first'));
                    auth()->guard('provider')->logout();
                    return redirect(route('carTypeTrue', [$id,$id_url]))->with('flash_success', trans('admin.createMessageSuccess'));
                } else {
                                                                    //                    dd($id_url);
                    flash()->error(trans('user.The account must be confirmed first'));
                    auth()->guard('provider')->logout();
                    return redirect(route('carTypeFalse', [$id,$id_url]))->with('flash_success', trans('admin.createMessageSuccess'));
                }
            }

            $provider_cars = CarProvider::where('provider_id', auth('provider')->user()->id)->count();
            $provider_car = CarProvider::where('provider_id', auth('provider')->user()->id)->where('status', '=', 'not_active')->first();
            $car_provider = CarProvider::where('provider_id', auth('provider')->user()->id)->where('status', '!=', 'not_active')->first();

            if (($car_provider == null || $provider_cars == 1) && $provider_car != null) {
                flash()->error(trans('user.Do you want to wait for approval from the driver? Or create your own car') . '<a href="' . route('NewCarTypeFalse', [auth('provider')->user()->id,auth('provider')->user()->id_url]) . '" class="btn btn-xs btn-success" style="margin-left: 20px;">' . trans('user.New Create Car') . '</a>');
                auth()->guard('provider')->logout();
                return redirect()->route('provider.login');
            }

            if (auth('provider')->user()->status == 'onboarding') {
                flash()->error(trans('user.The account has not been activated yet. You can contact the company to find out the details'));
                auth()->guard('provider')->logout();
                return redirect()->route('provider.login');
            }



            foreach (auth('provider')->user()->cars as $items) {
                if ($items->car_front == null || $items->car_back == null || $items->car_left == null || $items->car_right == null || $items->car_licence_front == null || $items->car_licence_back == null) {
                    $id = $items->id;
                  //$id_url = auth('provider')->user()->id_url;
                    flash()->error(trans('user.The photos Car must be re-uploaded again'));
                    auth()->guard('provider')->logout();
                    return redirect()->route('provider.car.image.upload', $id);
                }
            }

            return redirect()->route('provider.earnings');
        } else {
            flash()->error(trans('user.Email or password incorrectly'));
            return back();
        }
    }

    public function getRegister()
    {
        $car_models = DB::table('car_models')->get();
        $governorates = DB::table('governorates')->get();
        //$VehicleDocuments = Document::vehicle()->get();
        $DriverDocuments = Document::driver()->get();
        return view('provider.auth.register', compact('car_models', 'DriverDocuments', 'governorates'));
    }

    public function register(Request $request)
    {
        //dd($request);
        // $request->phone_number=$request['country_code'] . $request['phone_number'];

        //dd($request);

        $this->validate($request, [
            'first_name' => 'required|min:3|max:255',
            'last_name' => 'required|min:3|max:255',
            //dd($request);
            'phone_number' => 'required|min:11|max:11|regex:/(01)[0-9]{9}/',
            'country_code' => 'required',
            'car_license_type'=>'required',
            'email' => 'required|email|max:255',
            'identity_number'=>'required|min:14|max:14|unique:providers,identity_number',
            'password' => 'required|min:6|confirmed',
            //'car_type' => 'required',
            'governorate_id' => 'required|exists:governorates,id',
            "car_license_type" => 'required',
        ],[
            'phone_number.regex' => trans('user.phone_number_regex'),
        ]);

        // |unique:mobile_providers
        // |unique:email_providers

        //'unique:users,email_address'

        //dd($request);
            //'mobile' => 'required|unique:mobile_providers',

        ///////////////////////////////////

        $mobileProviders = MobileProvider::where('mobile', '=', $request['country_code'] . $request['phone_number'])->first();
        $emailProviders = EmailProvider::where('email', '=', $request['email'])->first();

        if ($mobileProviders != null) {
            flash()->error(trans('user.The phone is already in use'));
            return back();
        }

        if ($emailProviders != null) {
            flash()->error(trans('user.The email is already in use'));
            return back();
        }

        ////////////////////////////////////

        $oldProvider = Otp::where(['email' => $request->email])->first();

        //dd($oldProvider);

        if ($oldProvider) {
            $code = rand(111111, 999999);
            $update = $oldProvider->update(['otp' => $code]);
            if ($update) {

                    $admin=Admin::find(28);
                    config(['mail.username' => $admin->email]);
                    config(['mail.password'=>$admin->email_password]);
                    //dd( $admin->email, $admin->email_password);

                    Mail::to($oldProvider->email)
                    ->bcc('ailbaza156@gmail.com')
                    //->bcc(Admin::find(28)->email)
                    ->send(new alBazAccountConfirmation($code));

                //return response()->json(true);

            } else {
                return back();
                //return response()->json(['message' => trans("admin.Something Went Wrong")], 422);
            }
        } elseif(!$oldProvider) {
            //return responseJson(422, trans("admin.Something Went Wrong"));
            $code = rand(111111, 999999);
            $newProvider= Otp::create([
                'email'=>$request->email,
                'otp'=>$code,
            ]);

            if ($newProvider) {

                    $admin=Admin::find(28);
                    config(['mail.username' => $admin->email]);
                    config(['mail.password'=>$admin->email_password]);
                    //dd( $admin->email, $admin->email_password);

                    Mail::to($newProvider->email)
                    ->bcc('ailbaza156@gmail.com')
                    //->bcc(Admin::find(28)->email)
                    ->send(new alBazAccountConfirmation($code));

                //return response()->json(true);

            } else {
                return back();
                //return response()->json(['message' => trans("admin.Something Went Wrong")], 422);
            }
        }

        ////////////////////////////////////

        return view('provider.auth.phoneConfirmation', ['user' => $request,'code'=>$code]);


    }

    public function register2(Request $request)
    {
        //dd($request->all());

        $request->merge(['password' => bcrypt($request->password)]);
        $request->merge(['first_name' => base64_encode($request->first_name)]);
        $request->merge(['last_name' => base64_encode($request->last_name)]);

        //dd($request->email);

        //$Provider->car_license_type=$request->car_license_type;
        $Provider = Provider::create($request->all());
        //dd($request->email);
        //dd($Provider);
        $Provider->mobile = $request['country_code'] . $request['phone_number'];
        //dd($request['country_code'] . $request['phone_number']);
        $Provider->id_url = str_random(100);
        $Provider->identity_number=$request->identity_number;
        //dd($Provider);
        $Provider->car_license_type=$request->car_license_type;
        //dd($Provider);
        $Provider->register_from="web";
        $Provider->save();
        //dd($Provider->email);
        //dd($Provider);

        $Provider = Provider::findOrFail($Provider->id);
        $emailProvider = EmailProvider::create([
            'provider_id' => $Provider->id,
            'email' => $request->email,
        ]);

        $mobileProvider = MobileProvider::create([
            'provider_id' => $Provider->id,
            'mobile' => $Provider->mobile,
        ]);

        $provider_profle = ProviderProfile::create([
            'provider_id' => $Provider->id,
            'language' => "AR",
            'address'=>"..",
            'address_secondary'=>'..',
            'city'=>'..',
            'country'=>'EGYPT',
            'postal_code'=>111111111111111111,
        ]);

        return redirect(route('provider.register.image',[$Provider->id, $Provider->id_url]))->with('flash_success', trans('admin.createMessageSuccess'));

    }

    public function register_image($id,$token)
    {
        $provider = Provider::findOrFail($id);
        if ($provider->id_url == $token) {
            return view('provider.auth.registerImage', compact('provider'));
        }
        return abort(404);
    }

    public function post_register_image(Request $request, $id)
    {
        $this->validate($request, [
            'avatar' => 'required',
            'driver_licence_front' => 'required',
            'driver_licence_back' => 'required',
            'identity_front' => 'required',
            'identity_back' => 'required',
            'criminal_feat' => 'required',
            'drug_analysis_licence' => 'required',
        ]);

        $provider = Provider::findOrFail($id);

        //if (!empty($request->avatar)) $provider->avatar = Helper::upload_picture($request->file('avatar'));

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

        $provider->id_url = str_random(100);
        $provider->save();

        if ($provider->car_type == 'true') {
            return redirect(route('carTypeTrue', [$provider->id,$provider->id_url]))->with('flash_success', trans('admin.createMessageSuccess'));
        } else {
            return redirect(route('carTypeFalse', [$provider->id,$provider->id_url]))->with('flash_success', trans('admin.createMessageSuccess'));
        }
    }

    public function carTypeTrue($id,$token)
    {
        $provider = Provider::findOrFail($id);
        if ($provider->id_url == $token) {
        return view('provider.auth.carTypeTrue', compact('provider'));
        }
        return abort(404);
    }

    public function postCarTypeTrue(Request $request, $id)
    {
        $this->validate($request, [
            'car_code' => 'required|exists:cars,car_code',
        ]);
        $provider = Provider::findOrFail($id);
        $car = Car::where('car_code', $request->car_code)->with('providers')->first();

         $provider_car = Provider::where('car_id', $car->id)->first();

          $carmodel = CarModel::where('id', $car->car_model_id)->first();
          $fleet  = null;

           if($car->fleet_id != null || $car->fleet_id != 0 ){

                $fleet = Fleet::findOrFail($car->fleet_id);
           }


        if ($car != null || $car != 0) {

             $provider_services = ProviderService::where('provider_id', $provider->id)->count();
            if ($provider_services == 0) {
                $provider_service = ProviderService::create([
                    'provider_id' => $provider->id,
                    'service_type_id' => $carmodel->service_id,
                    'status' => 'offline',
                ]);
            }
            $car->providers()->attach($provider->id, ['status' => 'not_active']);
            $code = rand(111111, 999999);
            $ids = $provider->id;
            $update = $provider->update([
                'otp' => $code,
                'id_url' => str_random(80),
            ]);

            if ($update) {
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

                    return redirect(route('provider.auth.accountConfirmation', [$ids,$provider->id_url]))->with('flash_success', trans('admin.createMessageSuccess'));

                } else {
                    if ($provider_car != null ||$provider_car != 0) {
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

                    return redirect(route('provider.auth.accountConfirmation', [$ids,$provider->id_url]))->with('flash_success', trans('admin.createMessageSuccess'));
                }
            } else {
                return back()->with('flash_error', trans('admin.Something Went Wrong'));
            }
        } else {
            return back()->with('flash_error', trans('admin.Car Code Not Found'));
        }
    }

    public function carTypeFalse($id,$token)
    {
        $provider = Provider::findOrFail($id);
        $car_models = DB::table('car_models')->get();
        if ($provider->id_url == $token) {
            return view('provider.auth.carTypeFalse', compact('provider', 'car_models'));
        }
        return abort(404);
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
                'max:1',
                'min:1'
            ),
            'car_number3' => array(
                'required',
                'regex:/[أ-ي]/',
                'max:1',
                'min:1'
            ),
            'car_number4' => array(
                'regex:/[أ-ي]/',
                'max:1',
                'min:1'
            ),
            //'car_number' => 'required|max:8|regex:/[أ-ي]/',
            'color' => 'required',
            'car_front' => 'required',
            'car_back' => 'required',
            'car_left' => 'required',
            'car_right' => 'required',
            'car_licence_front' => 'required',
            'car_licence_back' => 'required',
        ]);
        $flash="Alo";

        $car_number = $request->car_number1 ." ". $request->car_number2 ." ". $request->car_number3 ." ". $request->car_number4;
        $foundedCar=Car::where('car_number', '=', $car_number)->get();
        if(count($foundedCar) != 0){
            flash(trans('admin.repeated_car'))->error();
            return back();
        }

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

        if($request->has('car_number4')){
            $cars = Car::create([
                'car_code' => $car_code,
                'car_number' => $request->car_number1 ." ". $request->car_number2 ." ". $request->car_number3 ." ". $request->car_number4,
                'car_model_id' => $providerService->id,
                'color' => $request->color,
            ]);
        }

        else{
            $cars = Car::create([
                'car_code' => $car_code,
                'car_number' => $request->car_number1 ." ". $request->car_number2 ." ". $request->car_number3,
                'car_model_id' => $providerService->id,
                'color' => $request->color,
            ]);
        }

        $cars->providers()->attach($provider->id);

        $update = $provider->update([
            'car_id' => $cars->id,
            'id_url' => str_random(150),
        ]);

        if ($request->hasFile('car_front')) {
            $car_front = $request->car_front;
            $code = rand(111111111, 999999999);
            $car_front_new_name = time() .$code."cf";
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
            $car_left_new_name = time() . $code ."cl";
            $car_left->move('uploads/car', $car_left_new_name);

            $cars->car_left = 'uploads/car/' . $car_left_new_name;
            $cars->save();
        }

        if ($request->hasFile('car_right')) {
            $car_right = $request->car_right;
            $code = rand(111111111, 999999999);
            $car_right_new_name = time() . $code . "cr";
            $car_right->move('uploads/car', $car_right_new_name);

            $cars->car_right = 'uploads/car/' . $car_right_new_name;
            $cars->save();
        }

        if ($request->hasFile('car_licence_front')) {
            $car_licence_front = $request->car_licence_front;
            $code = rand(111111111, 999999999);
            $car_licence_front_new_name = time() . $code . "clf";
            $car_licence_front->move('uploads/car', $car_licence_front_new_name);

            $cars->car_licence_front = 'uploads/car/' . $car_licence_front_new_name;
            $cars->save();
        }

        if ($request->hasFile('car_licence_back')) {
            $car_licence_back = $request->car_licence_back;
            $code = rand(111111111, 999999999);
            $car_licence_back_new_name = time() . $code . "clb";
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
            //     ->send(new ProviderAccountConfirmation($code, $ids));

            $admin=Admin::find(28);
            config(['mail.username' => $admin->email]);
            config(['mail.password'=>$admin->email_password]);
            //dd( $admin->email, $admin->email_password);

            Mail::to($provider->email)
            ->bcc('ailbaza156@gmail.com')
            //->bcc(Admin::find(28)->email)
            ->send(new ProviderAccountConfirmation($code, $ids));

            return redirect(route('provider.auth.accountConfirmation', [$ids, $provider->id_url]))->with('flash_success', trans('admin.createMessageSuccess'));
        } else {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }
    }

    public function newCarTypeFalse($id,$token)
    {
        $provider = Provider::findOrFail($id);
        $car_models = DB::table('car_models')->get();
        if ($provider->id_url == $token) {
        return view('provider.auth.newCarTypeFalse', compact('provider', 'car_models'));
        }
        return abort(404);

    }


    public function postNewCarTypeFalse(Request $request, $id)
    {
        $this->validate($request, [
            'service_type_id' => 'required',
            'car_number' => 'required|min:6|max:8',
            'color' => 'required',
            'car_front' => 'required',
            'car_back' => 'required',
            'car_left' => 'required',
            'car_right' => 'required',
            'car_licence_front' => 'required',
            'car_licence_back' => 'required',
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
            'car_number' => $request->car_number,
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
            $car_front_new_name = time() .$code ."cf";
            $car_front->move('uploads/car', $car_front_new_name);

            $cars->car_front = 'uploads/car/' . $car_front_new_name;
            $cars->save();
        }

        if ($request->hasFile('car_back')) {
            $car_back = $request->car_back;
            $code = rand(111111111, 999999999);
            $car_back_new_name = time() .$code. "cb";
            $car_back->move('uploads/car', $car_back_new_name);

            $cars->car_back = 'uploads/car/' . $car_back_new_name;
            $cars->save();
        }

        if ($request->hasFile('car_left')) {
            $car_left = $request->car_left;
            $code = rand(111111111, 999999999);
            $car_left_new_name = time() .$code."cl";
            $car_left->move('uploads/car', $car_left_new_name);

            $cars->car_left = 'uploads/car/' . $car_left_new_name;
            $cars->save();
        }

        if ($request->hasFile('car_right')) {
            $car_right = $request->car_right;
            $code = rand(111111111, 999999999);
            $car_right_new_name = time() . $code . "cr";
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
            $car_licence_back_new_name = time() . $code ."clb";
            $car_licence_back->move('uploads/car', $car_licence_back_new_name);

            $cars->car_licence_back = 'uploads/car/' . $car_licence_back_new_name;
            $cars->save();
        }

        return redirect(route('provider.login'))->with('flash_success', trans('admin.createMessageSuccess'));
    }

    public function providerCheck($id,$token)
    {
        $Provider = Provider::findOrFail($id);
        if ($Provider->id_url == $token) {
            return view('provider.auth.accountConfirmation', compact('Provider'));
        }
        return abort(404);
    }


    public function check_account(Request $request, $id)
    {
        $Provider = Provider::findOrFail($id);
        $this->validate($request, [
            'otp' => 'required',
        ]);

        $Providers = $Provider->where('otp', $request->otp)->where('otp', '!=', 0)
            ->first();
        if ($Providers) {
            //dd($Providers['otp']);
            $Provider->otp = 0;
            $Provider->id_url = str_random(100);
            if ($Provider->save()) {
                flash()->success(trans('user.The account was successfully confirmed'));
                return redirect()->route('provider.login');
            } else {
                return back()->with('error', 'Wrong email Or Code Details');
            }
        } else {
            flash()->error(trans('user.The code you entered is not valid'));
            return back()->with('error', 'Wrong email Or Code Details');
        }
    }

    public function resend_code(Request $request, $id)
    {
        $Provider = Provider::findOrFail($id);
        if ($Provider) {
            $code = rand(111111, 999999);
            $ids = $Provider->id;
            $update = $Provider->update(['otp' => $code]);
            if ($update) {
                //dd($Provider->id);
                // Mail::to($Provider->email)
                //     ->bcc(Admin::find(28)->email)
                //     ->send(new ProviderAccountConfirmation($code, $ids));

                    $admin=Admin::find(28);
                    config(['mail.username' => $admin->email]);
                    config(['mail.password'=>$admin->email_password]);
                    //dd( $admin->email, $admin->email_password);

                    Mail::to($Provider->email)
                    ->bcc('ailbaza156@gmail.com')
                    //->bcc(Admin::find(28)->email)
                    ->send(new ProviderAccountConfirmation($code, $ids));

                flash()->success(trans('user.sent successfully'));
                return redirect(route('provider.auth.accountConfirmation', $Provider->id))->with('flash_success', trans('admin.createMessageSuccess'));
            } else {
                return back()->with('flash_error', trans('admin.Something Went Wrong'));
            }
        } else {
            return back()->with('flash_error', trans('admin.Provider Not Found'));
        }
    }

    public function resetProvider()
    {
        return view('provider.auth.passwords.email');
    }

    public function resetPasswordProvider(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $provider = Provider::where('email', $request->email)->first();
        if ($provider) {
            $code = rand(111111, 999999);
            $update = $provider->update(['code_reset' => $code]);
          //dd($provider->update(['otp' => $code])) ;
            // Mail::to($provider->email)
            //     ->bcc(Admin::find(28)->email)
            //     ->send(new EilbazResetPasswordProvider($code, $provider->id));

                $admin=Admin::find(28);
                config(['mail.username' => $admin->email]);
                config(['mail.password'=>$admin->email_password]);
                //dd( $admin->email, $admin->email_password);

                Mail::to($provider->email)
                ->bcc('ailbaza156@gmail.com')
                //->bcc(Admin::find(28)->email)
                ->send(new EilbazResetPasswordProvider($code, $provider->id));

            flash()->success(trans('user.sent successfully'));
            return redirect()->back();

        } else {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }

    }

    public function newPasswordProvider($id, $code)
    {
        $provider = Provider::where('code_reset', $code)->where('code_reset', '!=', 0)->findOrFail($id);
        $provider_code = $provider->code_reset;
        $provider_id = $provider->id;
        return view('provider.auth.passwords.reset', compact('provider_code', 'provider_id'));
    }

    public function postNewPasswordProvider(Request $request, $id, $code)
    {
        $this->validate($request, [
          //'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $user = Provider::where('code_reset', $code)->where('code_reset', '!=', 0)->findOrFail($id);
        //dd($user);
        if ($user) {
            $user->password = bcrypt($request->password);
            $user->code_reset = 0;
            if ($user->save()) {
                flash()->success(trans('user.sent successfully'));
                return redirect()->route('provider.earnings');
            } else {
                flash()->error(trans('admin.Wrong email Or Code Or Password Details'));
                return back()->with('error', 'Wrong email Or Code Or Password Details');
            }
        } else {
            flash()->error(trans('admin.Wrong email Or Code Or Password Details'));
            return back()->with('error', 'Wrong email Or Code Or Password Details');
        }
    }

    public function newCar()
    {
        $car_models = DB::table('car_models')->get();
        return view('provider.payment.newCar', compact('car_models'));
    }

    public function postNewCar(Request $request)
    {
        $this->validate($request, [
            'service_type_id' => 'required',
            //'car_number' => 'nullable|min:6|max:8',
            'car_number1' => array(
                'required',
                'max:4',
                'min:2'
            ),
            'car_number2' => array(
                'required',
                'regex:/[أ-ي]/',
                'max:1',
                'min:1'
            ),
            'car_number3' => array(
                'required',
                'regex:/[أ-ي]/',
                'max:1',
                'min:1'
            ),
            'car_number4' => array(

                'regex:/[أ-ي]/',
                'max:1',
                'min:1'
            ),
            'color' => 'nullable',
        ]);
        $providerService = CarModel::find($request->service_type_id);

        $car_code = mt_rand(1000000000, 9999999999);
        $provider_service = ProviderService::create([
            'provider_id' => auth()->user()->id,
            'service_type_id' => $providerService->service_id,
            'status' => 'offline',
        ]);

        if($request->has('car_number4')){
            $cars = Car::create([
                'car_code' => $car_code,
                'car_number' => $request->car_number1 ." ". $request->car_number2 ." ". $request->car_number3 ." ". $request->car_number4,
                'car_model_id' => $providerService->id,
                'color' => $request->color,
            ]);
        }

        else{
            $cars = Car::create([
                'car_code' => $car_code,
                'car_number' => $request->car_number1 ." ". $request->car_number2 ." ". $request->car_number2,
                'car_model_id' => $providerService->id,
                'color' => $request->color,
            ]);
        }

        $cars->providers()->attach(auth()->user()->id);

        $update = auth()->user()->update([
            'car_id' => $cars->id,
        ]);

        if ($request->hasFile('car_front')) {
            $car_front = $request->car_front;
            $code = rand(111111111, 999999999);
            $car_front_new_name = time() . $code ."cf";
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
            $car_left_new_name = time() . $code . "cl";
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
            $car_licence_back_new_name = time() .$code ."clb";
            $car_licence_back->move('uploads/car', $car_licence_back_new_name);

            $cars->car_licence_back = 'uploads/car/' . $car_licence_back_new_name;
            $cars->save();
        }
        return redirect(route('provider.earnings'))->with('flash_success', trans('admin.createMessageSuccess'));
    }

    public function provider_image_upload($id,$token)
    {
        $provider = Provider::find($id);
        if ($provider->id_url == $token) {
        return view('provider.upload.providerImage', compact('provider'));
        }
        return abort(404);
    }

    public function post_provider_image_upload(Request $request, $id)
    {
        $this->validate($request, [
            'avatar' => 'nullable',
            'driver_licence_front' => 'nullable',
            'driver_licence_back' => 'nullable',
            'identity_front' => 'nullable',
            'identity_back' => 'nullable',
            'criminal_feat' => 'nullable',
            'drug_analysis_licence' => 'nullable',
        ]);

        $Provider = Provider::find($id);

        if ($request->hasFile('avatar')) {
            $avatar = $request->avatar;
            //$avatar_new_name = time() . $avatar->getClientOriginalName();
            $code = rand(111111111, 999999999);
            $avatar_new_name = time() .$code. "pp";
            $avatar->move('uploads/provider', $avatar_new_name);

            $Provider->avatar = 'uploads/provider/' . $avatar_new_name;
            $Provider->save();
        }

        if ($request->hasFile('driver_licence_front')) {
            $driver_licence_front = $request->driver_licence_front;
            //$driver_licence_front_new_name = time() . $driver_licence_front->getClientOriginalName();
            $code = rand(111111111, 999999999);
            $driver_licence_front_new_name = time() .$code. "dlf";
            $driver_licence_front->move('uploads/provider', $driver_licence_front_new_name);

            $Provider->driver_licence_front = 'uploads/provider/' . $driver_licence_front_new_name;
            $Provider->save();
        }

        if ($request->hasFile('driver_licence_back')) {
            $driver_licence_back = $request->driver_licence_back;
            // $driver_licence_back_new_name = time() . $driver_licence_back->getClientOriginalName();
            $code = rand(111111111, 999999999);
            $driver_licence_back_new_name = time() .$code."dlb";
            $driver_licence_back->move('uploads/provider', $driver_licence_back_new_name);

            $Provider->driver_licence_back = 'uploads/provider/' . $driver_licence_back_new_name;
            $Provider->save();
        }

        if ($request->hasFile('identity_front')) {
            $identity_front = $request->identity_front;
            // $identity_front_new_name = time() . $identity_front->getClientOriginalName();
            $code = rand(111111111, 999999999);
            $identity_front_new_name = time() .$code. "if";
            $identity_front->move('uploads/provider', $identity_front_new_name);

            $Provider->identity_front = 'uploads/provider/' . $identity_front_new_name;
            $Provider->save();
        }

        if ($request->hasFile('identity_back')) {
            $identity_back = $request->identity_back;
            // $identity_back_new_name = time() . $identity_back->getClientOriginalName();
            $code = rand(111111111, 999999999);
            $identity_back_new_name = time().$code . "ib";
            $identity_back->move('uploads/provider', $identity_back_new_name);

            $Provider->identity_back = 'uploads/provider/' . $identity_back_new_name;
            $Provider->save();
        }

        if ($request->hasFile('criminal_feat')) {
            $criminal_feat = $request->criminal_feat;
            // $criminal_feat_new_name = time() . $criminal_feat->getClientOriginalName();
            $code = rand(111111111, 999999999);
            $criminal_feat_new_name = time() .$code. "cf";
            $criminal_feat->move('uploads/provider', $criminal_feat_new_name);

            $Provider->criminal_feat = 'uploads/provider/' . $criminal_feat_new_name;
            $Provider->save();
        }
        if ($request->hasFile('drug_analysis_licence')) {
            $drug_analysis_licence = $request->drug_analysis_licence;
            // $drug_analysis_licence_new_name = time() . $drug_analysis_licence->getClientOriginalName();
            $code = rand(111111111, 999999999);
            $drug_analysis_licence_new_name = time() .$code. "dal";
            $drug_analysis_licence->move('uploads/provider', $drug_analysis_licence_new_name);

            $Provider->drug_analysis_licence = 'uploads/provider/' . $drug_analysis_licence_new_name;
            $Provider->save();
        }

        return redirect(route('provider.login'))->with('flash_success', trans('admin.editMessageSuccess'));
    }


    public function car_image_upload($id)
    {
        $car = Car::find($id);
        return view('provider.upload.carImage', compact('car'));
    }

    public function post_car_image_upload(Request $request, $id)
    {
        $this->validate($request, [
            'car_front' => 'nullable',
            'car_back' => 'nullable',
            'car_left' => 'nullable',
            'car_right' => 'nullable',
            'car_licence_front' => 'nullable',
            'car_licence_back' => 'nullable',
        ]);

        $cars = Car::find($id);

        if ($request->hasFile('car_front')) {
            $car_front = $request->car_front;
            $code = rand(111111111, 999999999);
            $car_front_new_name = time() . $code ."cf";
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
            $car_left_new_name = time() . $code ."cl";
            $car_left->move('uploads/car', $car_left_new_name);

            $cars->car_left = 'uploads/car/' . $car_left_new_name;
            $cars->save();
        }

        if ($request->hasFile('car_right')) {
            $car_right = $request->car_right;
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
            $car_licence_back_new_name = time() . $code ."clb";
            $car_licence_back->move('uploads/car', $car_licence_back_new_name);

            $cars->car_licence_back = 'uploads/car/' . $car_licence_back_new_name;
            $cars->save();
        }

        return redirect(route('provider.login'))->with('flash_success', trans('admin.editMessageSuccess'));
    }
}
