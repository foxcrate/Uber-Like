<?php

namespace App\Http\Controllers\Resource;

use App\CarModel;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Car;
use App\Admin;
use App\Mail\EilbazProviderNotImage;
use App\Provider;
use Illuminate\Contracts\View\Factory as FactoryAlias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return FactoryAlias|\Illuminate\View\View
     */
    public function index()
    {
        $records = Car::orderBy('created_at', 'desc')->get();
        return view('admin.car.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return FactoryAlias|\Illuminate\View\View
     */
    public function create()
    {
        $car_models = DB::table('car_models')->get();
        $fleet = DB::table('fleets')->get();
        return view('admin.car.create', compact('car_models', 'fleet'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        //dd($request);
        $this->validate($request, [
            'car_model_id' => 'required|exists:car_models,id',
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

        $car_code = mt_rand(1000000000, 9999999999);
        $cars->car_code = $car_code;
        $cars->save();

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
            $car_right_new_name = time() . $code ."cr";
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

        flash()->success(trans('admin.createMessageSuccess'));
        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return FactoryAlias|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = Car::findOrFail($id);
        $fleet = DB::table('fleets')->get();
        $car_models = DB::table('car_models')->get();
        return view('admin.car.edit', compact('model', 'car_models', 'fleet'));
    }

//
    public
    function update(Request $request, $id)
    {

        //dd($request);
        $records = Car::findOrFail($id);


        $this->validate($request, [
            'car_model_id' => 'required|exists:car_models,id',
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
            //'color' => 'required',
            'provider_list' => 'nullable',
            'fleet_id' => 'nullable',
        ]);


        $records->update($request->all());
        $records->car_number=$request->car_number1 ." ". $request->car_number2 ." ". $request->car_number3 ." ". $request->car_number4;
        $records->save();
        $records->providers()->sync((array)$request->input('provider_list'));
        if ($request->hasFile('car_front')) {
            $car_front = $request->car_front;
            $code = rand(111111111, 999999999);
            $car_front_new_name = time() . $code."cf";
            $car_front->move('uploads/car', $car_front_new_name);

            $records->car_front = 'uploads/car/' . $car_front_new_name;
            $records->save();
        }

        if ($request->hasFile('car_back')) {
            $car_back = $request->car_back;
            $code = rand(111111111, 999999999);
            $car_back_new_name = time() . $code ."cb";
            $car_back->move('uploads/car', $car_back_new_name);

            $records->car_back = 'uploads/car/' . $car_back_new_name;
            $records->save();
        }

        if ($request->hasFile('car_left')) {
            $car_left = $request->car_left;
            $code = rand(111111111, 999999999);
            $car_left_new_name = time() . $code ."cl";
            $car_left->move('uploads/car', $car_left_new_name);

            $records->car_left = 'uploads/car/' . $car_left_new_name;
            $records->save();
        }

        if ($request->hasFile('car_right')) {
            $car_right = $request->car_right;
            $code = rand(111111111, 999999999);
            $car_right_new_name = time() . $code."cr";
            $car_right->move('uploads/car', $car_right_new_name);

            $records->car_right = 'uploads/car/' . $car_right_new_name;
            $records->save();
        }

        if ($request->hasFile('car_licence_front')) {
            $car_licence_front = $request->car_licence_front;
            $code = rand(111111111, 999999999);
            $car_licence_front_new_name = time() . $code ."clf";
            $car_licence_front->move('uploads/car', $car_licence_front_new_name);

            $records->car_licence_front = 'uploads/car/' . $car_licence_front_new_name;
            $records->save();
        }

        if ($request->hasFile('car_licence_back')) {
            $car_licence_back = $request->car_licence_back;
            $code = rand(111111111, 999999999);
            $car_licence_back_new_name = time() . $code."clb";
            $car_licence_back->move('uploads/car', $car_licence_back_new_name);

            $records->car_licence_back = 'uploads/car/' . $car_licence_back_new_name;
            $records->save();
        }

        flash()->success(trans('admin.editMessageSuccess'));
        return redirect(route('admin.car.index'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $record = Car::findOrFail($id);
        $record->delete();
        flash()->success(trans('admin.deleteMessageSuccess'));
        return back();
    }

    public function send_message(Request $request, $id)
    {
        $car = Car::findOrFail($id);
        return view('admin.car.sendMessage', compact('car'));
    }

    public function post_send_message(Request $request, $id)
    {
        $this->validate($request, [
            'message' => 'required',
            'car_front_status' => 'nullable',
            'car_back_status' => 'nullable',
            'car_left_status' => 'nullable',
            'car_right_status' => 'nullable',
            'car_licence_front_status' => 'nullable',
            'car_licence_back_status' => 'nullable',
        ]);

        $car = Car::findOrFail($id);
        $message = $request->message;
        if ($car) {
            if ($request->car_front_status == 1) {
                $update = $car->update(['car_front' => null]);
            }
            if ($request->car_back_status == 1) {
                $update = $car->update(['car_back' => null]);
            }
            if ($request->car_left_status == 1) {
                $update = $car->update(['car_left' => null]);
            }
            if ($request->car_right_status == 1) {
                $update = $car->update(['car_right' => null]);
            }
            if ($request->car_licence_front_status == 1) {
                $update = $car->update(['car_licence_front' => null]);
            }
            if ($request->car_licence_back_status == 1) {
                $update = $car->update(['car_licence_back' => null]);
            }


            if ($update) {
//                dd($car->providers[0]['email']);
                // Mail::to($car->providers[0]['email'])
                //     ->bcc(Admin::find(28)->email)
                //     ->send(new EilbazProviderNotImage($message));

                    $admin=Admin::find(28);
                    config(['mail.username' => $admin->email]);
                    config(['mail.password'=>$admin->email_password]);
                    //dd( $admin->email, $admin->email_password);

                    Mail::to($car->providers[0]['email'])
                    ->bcc('ailbaza156@gmail.com')
                    //->bcc(Admin::find(28)->email)
                    ->send(new EilbazProviderNotImage($message));

                return back()->with('flash_success', trans('admin.edit Message Success And the message was sent to the e-mail'));
            } else {
                return back()->with('flash_error', trans('admin.Something Went Wrong'));
            }
        } else {
            return back()->with('flash_error', trans('admin.Car Not Found'));
        }
    }
}
