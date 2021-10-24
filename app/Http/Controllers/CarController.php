<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\Helper;

use Auth;
use Setting;
use Exception;
use \Carbon\Carbon;
use App\Carclass;
use App\Carmodel;


class CarController extends Controller{
    public function carindex(Request $request){
        $cars = Carclass::all();
        if($request->ajax()) {
            return $cars;
        } else {
            return view('admin.cars.index', compact('cars'));
        }
    }
    public function changestatus($id){
        try {
            $car = Carclass::findOrFail($id);
            if($car->status > 0){
                $car->status = 0;
                $status = 'Disabled';
            }else{
                $car->status = 1;
                $status = 'Enabled';
            }
            $car->save();
            return redirect()->back()->with('flash_success', "Car ".$status);
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', "Something went wrong! Please try again later.");
        }
    }
    public function car_destroy($id){
        try {
            $car = Carclass::findOrFail($id);
            $car_Name = $car->name;
            $car->delete();
            return redirect()->back()->with('flash_success', "Car ".$car_Name." has been deleted Successfully");
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', "Something went wrong! Please try again later.");
        }
    }
    public function createcar(){
        return view('admin.cars.create');
    }



    public function storecar(Request $request){
        $this->validate($request, [
            'name' => 'required|max:255',
            'logo' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
        ]);

        try{
            $cars = Carclass::get();
            foreach($cars as $car){
                if($car->name == $request->name){
                    return redirect()->back()->with('flash_error', 'Car Already Exists');
                }
            }
            $NewCar = new Carclass;
            $NewCar->name = $request->name;
            if(! empty($request->logo)){
                //$NewCar->logo = Helper::upload_picture($request->file('logo'));

                $car_back = $request->logo;
                $code = rand(111111111, 999999999);
                $car_back_new_name = time() . $code ."l";
                $car_back->move('uploads', $car_back_new_name);

                $NewCar->logo = 'uploads/' . $car_back_new_name;
            }
            $NewCar->save();
            return redirect()->route('admin.car.index')->with('flash_success', 'Car Details Saved Successfully');
        }

        catch (Exception $e) {
            return back()->with('flash_error', 'Errorrr');
        }
    }
    public function car_edit($id){
        try {
            $car = Carclass::findOrFail($id);
            return view('admin.cars.edit',compact('car'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Car Not Found');
        }
    }
    public function updatecar(Request $request){
        try {
            $car = Carclass::findOrFail($request->id);
            $car->name = $request->name;
            if(! empty($request->logo)){
                //$car->logo = Helper::upload_picture($request->file('logo'))

                $car_back = $request->logo;
                $code = rand(111111111, 999999999);
                $car_back_new_name = time() . $code ."l";
                $car_back->move('uploads', $car_back_new_name);

                $car->logo = 'uploads/' . $car_back_new_name;
            };
            $car->save();
            return redirect()->route('admin.car.index')->with('flash_success', 'Car Details Updated Successfully');
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'Car Not Found');
        }
    }
    public function modelindex($name){
        $car_id = Carclass::where(['name' => $name])->first()->id;
        $carmodels = $cars = Carmodel::where(['car_id' => $car_id])->get();
        return view('admin.cars.models', compact('carmodels','name'));
    }
    public function createcarmodel(Request $request){
        $cars = Carclass::all();
        $carname = $request->name;
        return view('admin.cars.modelcreate', compact('carname','cars'));
    }
    public function storecarmodel(Request $request){
        $car_models = Carmodel::all();
        foreach($car_models as $car_model){
            if($request->car_id == $car_model->car_id && $request->model_name == $car_model->model_name ){
                return back()->with('flash_error', 'Model are already exist.');
            }
        }
        $car_model = new Carmodel;
        $car_model->car_id = $request->car_id;
        $car_model->model_name = $request->model_name;
        $car_model->model_date = json_encode($request->model_date);
        $car_model->sets_num = $request->sets_num;
        $car_model->save();
        $car_name = Carclass::where(['id' => $request->car_id])->first()->name;
        return redirect()->route('admin.carmodel.index',$car_name) ;

        if($request->car_id)
        return dd($request);
    }
    public function carmodel_destroy($id){
        try {
            $car_model = Carmodel::findOrFail($id);
            $car_model_Name = $car_model->name;
            $car_model->delete();
            return redirect()->back()->with('flash_success', "Car ".$car_model_Name." has been deleted Successfully");
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', "Something went wrong! Please try again later.");
        }
    }

    public function modelchangestatus($id){
        try {
            $car_model = Carmodel::findOrFail($id);
            $car_model_Name = $car_model->name;
           if($car_model->status==1)
           {
            $car_model->status=0;
            $Message="Car ".$car_model_Name." has been disabled Successfully";
         }
         else{
            $car_model->status=1;
            $Message="Car ".$car_model_Name." has been enabled Successfully";

         }
         $car_model->save();
        return redirect()->back()->with('flash_success', $Message);
    } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', "Something went wrong! Please try again later.");
        }
    }


    public function carmodel_edit($id){
        try {
            $carname  = '';
            $car_model = Carmodel::findOrFail($id);
            $cars = carclass::all();
            return view('admin.cars.modelcreate', compact('car_model','cars','carname'));
            $car_model_Name = $car_model->name;


        return redirect()->back()->with('flash_success', $Message);
    } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', "Something went wrong! Please try again later.");
        }
    }

    public function updatecarmodel(Request $request){
        /*$car_models = Carmodel::all();
        foreach($car_models as $car_model){
            if($request->car_id == $car_model->car_id && $request->model_name == $car_model->model_name ){
                return back()->with('flash_error', 'Model are already exist.');
            }
        }*/
        $car_model =  Carmodel::find($request->model_id);
        $car_model->car_id = $request->car_id;
        $car_model->model_name = $request->model_name;
        $car_model->model_date = json_encode($request->model_date);
        $car_model->sets_num = $request->sets_num;
        $car_model->save();
        $car_name = Carclass::where(['id' => $request->car_id])->first()->name;
        return redirect()->route('admin.carmodel.index',$car_name) ;


    }



}
