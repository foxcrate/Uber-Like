<?php

namespace App\Http\Controllers\Company;

use App\Car;
use App\CarModel;
use App\Fleet;
use App\Helpers\Helper;
use App\Http\Controllers\ProviderResources\ProfileController;
use App\Mail\EilbazCreatedProvider;
use App\Mail\EilbazResetPasswordUser;
use App\Mail\UserAccountConfirmation;
use App\Provider;
use App\ProviderService;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{
    //
    public function index()
    {
//        dd(auth('fleet')->user()->cars);
        ProfileController::checkData();
        return view('company.dashboard');
    }

    public function profile()
    {
        //dd(auth('fleet'));
        //auth()->guard('fleet')->logout();
        //return 'Done';
        return view('company.profile');
    }

    public function updateProfile(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|max:255',
            'company' => 'required|max:255',
            'mobile' => 'required|numeric',
//            'number_tax_card' => 'required|numeric',
        ]);

        try {
            $fleet = Fleet::findOrFail(auth('fleet')->user()->id);
            $fleet->update($request->except('password'));

            if ($request->hasFile('logo')) {
                $logo = $request->logo;
                $code = rand(111111111, 999999999);
                $logo_new_name = time() . $code."l";
                $logo->move('uploads/fleet', $logo_new_name);

                $fleet->logo = 'uploads/fleet/' . $logo_new_name;
                $fleet->save();
            }

            if ($request->hasFile('tax_card')) {
                $tax_card = $request->tax_card;
                $code = rand(111111111, 999999999);
                $tax_card_new_name = time() . $code."tc";
                $tax_card->move('uploads/fleet', $tax_card_new_name);

                $fleet->tax_card = 'uploads/fleet/' . $tax_card_new_name;
                $fleet->save();
            }

            if ($request->hasFile('commercial_register')) {
                $commercial_register = $request->commercial_register;
                $code = rand(111111111, 999999999);
                $commercial_register_new_name = time() .$code."cr";
                $commercial_register->move('uploads/fleet', $commercial_register_new_name);

                $fleet->commercial_register = 'uploads/fleet/' . $commercial_register_new_name;
                $fleet->save();
            }

            flash()->success(trans('admin.editMessageSuccess'));
            return redirect()->route('company.profile')->with('flash_success', trans('admin.editMessageSuccess'));

        } catch (ModelNotFoundException $e) {
            flash()->error(trans('admin.editMessageError'));
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }
    }

    public function showCar($id)
    {
        $car = Car::find($id);
//        dd($car->providers);
//        dd(auth('fleet')->user()->providers);
        return view('company.showCar', compact('car'));
    }

    public function showCarProvider($id)
    {
        $provider = Provider::find($id);
        return view('company.showCarProvider', compact('provider'));
    }

    public function showProvider($id)
    {
        $provider = Provider::find($id);
        return view('company.showProvider', compact('provider'));
    }

    public function showProviderCar($id)
    {
        $car = Car::find($id);
        return view('company.showProviderCar', compact('car'));
    }

    public function addCar()
    {
        $car_models = DB::table('car_models')->get();
        return view('company.car.create', compact('car_models'));
    }

    public function postAddCar(Request $request)
    {
        //
        $this->validate($request, [
            'car_model_id' => 'required',
            //'car_number' => 'required|min:6|max:8',
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
            'color' => 'required',
            'provider_list' => 'nullable',
        ]);
        //$cars = Car::create($request->except('provider_list'));

        $car_number = $request->car_number1 ." ". $request->car_number2 ." ". $request->car_number3 ." ". $request->car_number4;
        $foundedCar=Car::where('car_number', '=', $car_number)->get();
        if(count($foundedCar) != 0){
            //return "Done";
            flash(trans('admin.repeated_car'))->error();
            return back();
        }

        $car_code = mt_rand(1000000000, 9999999999);
        if($request->has('car_number4')){
            $cars = Car::create([
                'car_code' => $car_code,
                'car_number' => $request->car_number1 ." ". $request->car_number2 ." ". $request->car_number3 ." ". $request->car_number4,
                'car_model_id' => $request->car_model_id,
                'color' => $request->color,
            ]);
        }

        else{
            $cars = Car::create([
                'car_code' => $car_code,
                'car_number' => $request->car_number1 ." ". $request->car_number2 ." ". $request->car_number3,
                'car_model_id' => $request->car_model_id,
                'color' => $request->color,
            ]);
        }

        $cars->providers()->attach($request->provider_list);

        // $car_code = mt_rand(1000000000, 9999999999);
        //$cars->car_code = $car_code;
        $cars->fleet_id = auth('fleet')->user()->id;
        $cars->save();

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
            $car_back_new_name = time() .$code."cb";
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
            $car_right_new_name = time() . $code."cr";
            $car_right->move('uploads/car', $car_right_new_name);

            $cars->car_right = 'uploads/car/' . $car_right_new_name;
            $cars->save();
        }

        if ($request->hasFile('car_licence_front')) {
            $car_licence_front = $request->car_licence_front;
            $code = rand(111111111, 999999999);
            $car_licence_front_new_name = time() . $code. "clf";
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

        flash()->success(trans('admin.createMessageSuccess'));
        return redirect()->back();
    }

    public function addProvider()
    {
        $car_models = DB::table('car_models')->get();
        return view('company.provider.create', compact('car_models'));
    }

    public function postAddProvider(Request $request)
    {
        //return $request;
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|unique:providers,email|email|max:255',
            'phone_number' => 'required|min:11|max:11',
            'country_code' => 'required',
            'avatar' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
            'password' => 'required|min:6|confirmed',
            'fleet' => 'nullable',
            'car_type' => 'required',
        ]);
        //dd('Alo');

        try {
            $providers = Provider::where('mobile', '=', $request['country_code'] . $request['phone_number'])->first();

            if ($providers != null) {
                flash()->error(trans('user.The phone is already in use'));
                return redirect()->back();
            }
            $provider = $request->all();
            $provider['password'] = bcrypt($request->password);
            $provider['fleet'] = $request->fleet;

            $provider = Provider::create($provider);
            $provider->mobile = $request['country_code'] . $request['phone_number'];
            $provider->save();
            $this->saveProviderImages($provider, $request);
            $provider->serviceTypes()->attach($request->service_type_id);

            if ($provider->car_type == 'true') {
                return redirect(route('company.carTypeTrue', $provider->id))->with('flash_success', trans('admin.createMessageSuccess'));
            } elseif ($provider->car_type == 'false') {
                return redirect(route('company.carTypeFalse', $provider->id))->with('flash_success', trans('admin.createMessageSuccess'));
            }
        } catch (Exception $e) {
            return back()->with('flash_error', trans('admin.Provider Not Found'));
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
        // if (!empty($request->driver_licence_front)) $pro->driver_licence_front = Helper::upload_picture($request->file('driver_licence_front'));
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


    public function carTypeTrue($id)
    {
        $provider = Provider::findOrFail($id);
        return view('company.provider.carTypeTrue', compact('provider'));
    }

    public function postCarTypeTrue(Request $request, $id)
    {
        $this->validate($request, [
            'car_code' => 'required',
        ]);

        $provider = Provider::findOrFail($id);
        //dd($provider);
        $car = Car::where('car_code', $request->car_code)->with('providers')->first();
        //dd($car);
        if ( $car !=null) {
            //dd("Alo");
            $provider_service = ProviderService::create([
                'provider_id' => $provider->id,
                'service_type_id' => $car->carModel->service_id,
                'status' => 'offline',
            ]);
            // dd("Alo2");

            $update = $provider->update([
                'fleet' => auth('fleet')->user()->id,
            ]);
            // dd("Alo3");

            $car->providers()->attach($provider->id, ['status' => 'active']);
            //dd($car);

            if ($update) {
                //dd('alo');
                return redirect(route('company.index'))->with('flash_success', trans('admin.createMessageSuccess'));
            } else {
                return back()->with('flash_error', trans('admin.Something Went Wrong'));
            }
        } else {
            return back()->with('flash_error', trans('admin.Car Code Not Found'));
        }
    }

    public function carTypeFalse($id)
    {
        $provider = Provider::findOrFail($id);
        $car_models = DB::table('car_models')->get();
        return view('company.provider.carTypeFalse', compact('provider', 'car_models'));
    }

    public function postCarTypeFalse(Request $request, $id)
    {
        $this->validate($request, [
            'service_type_id' => 'required',
            'car_number' => 'required|min:6|max:8',
            'color' => 'required',
        ]);

        $provider = Provider::findOrFail($id);
        $providerService = CarModel::find($request->service_type_id);

        $car_code = mt_rand(1000000000, 9999999999);
        $provider_service = ProviderService::create([
            'provider_id' => $provider->id,
            'service_type_id' => $providerService->service_id,
            'status' => 'offline',
        ]);

        $cars = Car::create([
            'car_code' => $car_code,
            'car_number' => $request->car_number,
            'car_model_id' => $providerService->id,
            'color' => $request->color,
            'fleet_id' => auth('fleet')->user()->id,
        ]);
        $cars->providers()->attach($provider->id);

        $update = $provider->update([
            'car_id' => $cars->id,
            'fleet' => auth('fleet')->user()->id,
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
            $car_back_new_name = time() . $code."cb";
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
            $car_right_new_name = time() . $code."cr";
            $car_right->move('uploads/car', $car_right_new_name);

            $cars->car_right = 'uploads/car/' . $car_right_new_name;
            $cars->save();
        }

        if ($request->hasFile('car_licence_front')) {
            $car_licence_front = $request->car_licence_front;
            $code = rand(111111111, 999999999);
            $car_licence_front_new_name = time() . $code."clf";
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

//        $code = rand(111111, 999999);
//        $ids = $provider->id;
//        $update = $provider->update([
//            'otp' => $code,
//        ]);
        if ($update) {

//            Mail::to($provider->email)
//                ->bcc('ailbaza156@gmail.com')
//                ->send(new EilbazCreatedProvider($code, $ids));
            return redirect(route('company.index'))->with('flash_success', trans('admin.createMessageSuccess'));
        } else {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }
    }


    public function removeCar($id)
    {
        $record = Car::findOrFail($id);
        $record->delete();
        flash()->success('تم الحذف بنجاح');
        return back();
    }

    public function removeProvider($id)
    {
        $record = Provider::findOrFail($id);
        $record->delete();
        flash()->success('تم الحذف بنجاح');
        return back();
    }

    public function editProvider($id)
    {
        $provider = Provider::findOrFail($id);
        $car_models = DB::table('car_models')->get();
        return view('company.provider.edit', compact('provider', 'car_models'));
    }

    public function updateProvider(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|unique:providers,email,' . $id,
            'mobile' => 'between:11,13',
            'avatar' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
        ]);

        try {

            $provider = Provider::findOrFail($id);

            $provider->first_name = $request->first_name;
            $provider->last_name = $request->last_name;
            $provider->mobile = $request->mobile;
            $provider->serviceTypes()->attach($request->service_type_id);

            if (request()->hasFile('avatar')) {
                $av = request()->avatar;
                $code = rand(111111111, 999999999);
                $car_front_new_name = time() . $code."pp";
                $av->move('uploads/provider', $car_front_new_name);

                $provider->avatar = 'uploads/provider/' . $car_front_new_name;
                //$records->save();
            }

            $provider->save();

            //$this->saveProviderImages($provider, $request);

            return redirect()->back()->with('flash_success', trans('admin.editMessageSuccess'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', trans('admin.Provider Not Found'));
        }
    }

    public function editCar($id)
    {
        $model = Car::findOrFail($id);
        $car_models = DB::table('car_models')->get();
        return view('company.car.edit', compact('model', 'car_models'));
    }

    public function updateCar(Request $request, $id)
    {
        $this->validate(request(), [
            'car_number' => 'required|min:6|max:8',
            'car_model_id' => 'required',
            'color' => 'required',
            'provider_list' => 'nullable',
            'car_front' => 'nullable',
            'car_back' => 'nullable',
            'car_left' => 'nullable',
            'car_right' => 'nullable',
            'car_licence_front' => 'nullable',
            'car_licence_back' => 'nullable',
        ]);
        $records = Car::find($id);
        $records->car_number = $request->car_number;
        $records->car_model_id = $request->car_model_id;
        $records->color = $request->color;
        $records->providers()->attach($request->input('provider_list'));
        $records->save();
        if (request()->hasFile('car_front')) {
            $car_front = request()->car_front;
            $code = rand(111111111, 999999999);
            $car_front_new_name = time() . $code."cf";
            $car_front->move('uploads/car', $car_front_new_name);

            $records->car_front = 'uploads/car/' . $car_front_new_name;
            $records->save();
        }

        if (request()->hasFile('car_back')) {
            $car_back = request()->car_back;
            $code = rand(111111111, 999999999);
            $car_back_new_name = time() . $code."cb";
            $car_back->move('uploads/car', $car_back_new_name);

            $records->car_back = 'uploads/car/' . $car_back_new_name;
            $records->save();
        }

        if (request()->hasFile('car_left')) {
            $car_left = request()->car_left;
            $code = rand(111111111, 999999999);
            $car_left_new_name = time() . $code."cl";
            $car_left->move('uploads/car', $car_left_new_name);

            $records->car_left = 'uploads/car/' . $car_left_new_name;
            $records->save();
        }

        if (request()->hasFile('car_right')) {
            $car_right = request()->car_right;
            $code = rand(111111111, 999999999);
            $car_right_new_name = time() . $code."cr";
            $car_right->move('uploads/car', $car_right_new_name);

            $records->car_right = 'uploads/car/' . $car_right_new_name;
            $records->save();
        }

        if (request()->hasFile('car_licence_front')) {
            $car_licence_front = request()->car_licence_front;
            $code = rand(111111111, 999999999);
            $car_licence_front_new_name = time() . $code."clf";
            $car_licence_front->move('uploads/car', $car_licence_front_new_name);

            $records->car_licence_front = 'uploads/car/' . $car_licence_front_new_name;
            $records->save();
        }

        if (request()->hasFile('car_licence_back')) {
            $car_licence_back = request()->car_licence_back;
            $code = rand(111111111, 999999999);
            $car_licence_back_new_name = time() . $code."clb";
            $car_licence_back->move('uploads/car', $car_licence_back_new_name);

            $records->car_licence_back = 'uploads/car/' . $car_licence_back_new_name;
            $records->save();
        }

        flash()->success(trans('admin.editMessageSuccess'));
        return back();

    }


    public function change_password()
    {
        return view('company.change_password');
    }

    public function update_password(Request $request)
    {
        if (!(Hash::check($request->get('current-password'), auth('fleet')->user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error", trans("admin.Your current password does not matches with the password you provided. Please try again."));
        }

        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            //Current password and new password are same
            return redirect()->back()->with("error", trans("admin.New Password cannot be same as your current password. Please choose a different password."));
        }

        $this->validate($request, [
            'current-password' => 'required',
            'new-password' => 'required|min:6|confirmed',
        ]);

        $user = auth('fleet')->user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with("success", trans("admin.Password changed successfully !"));

    }
}
