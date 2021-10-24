<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Setting;
use Exception;
use App\Helpers\Helper;

use App\CarModel;
use App\TransportationType;
use App\ServiceType;
use App\CarClass;

class CarModelResource extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $CarModels = CarModel::all();
        $Transtypes = TransportationType::where(['status' => '1'])->get();
        $Services = ServiceType::where(['status' => 1])->get();
        $CarClasses = CarClass::where(['status' => 1])->get();
        $CarModels = CarModel::all();
        if($request->ajax()) {
            return $CarClasses;
        } else {
            return view('admin.carmodel.index', compact('CarModels','Transtypes','Services','CarClasses'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request){
        $Car_Model = $request->all();
        if($request->id){
            $this->validate($request, [
                'name' => "required|max:255",
                'name_en' => "required|max:255",
                'date' => 'required|numeric',
                'transtype_id' => 'required|numeric',
                'service_id' => 'required|numeric',
                'carclass_id' => 'required|numeric'
            ]);
            try {
                $Car_Models = CarModel::get();
                foreach($Car_Models as $element){
                    if($element->name == $request->name &&
                    $element->date == $request->date &&
                    $element->transtype_id == $request->transtype_id &&
                    $element->carclass_id == $request->carclass_id &&
                    $element->id != $request->id){
                        return back()->with('flash_error', trans('admin.Car Model already Exists'));
                    }
                }
                $CarModel = CarModel::findOrFail($request->id);
                $CarModel->update($Car_Model);
                return back()->with('flash_success',trans('admin.editMessageSuccess'));
            } catch (Exception $e) {
                return back()->with('flash_error',trans('admin.Something Went Wrong'));
            }
        }else{
            
            $this->validate($request, [
                'name' => "required|max:255",
                'name_en' => "required|max:255",
                'date' => 'required|numeric',
                'transtype_id' => 'required|numeric',
                'service_id' => 'required|numeric',
                'carclass_id' => 'required|numeric'
            ]);
            try {
                $Car_Models = CarModel::get();
                foreach($Car_Models as $element){
                    if($element->name == $request->name &&
                    $element->date == $request->date &&
                    $element->transtype_id == $request->transtype_id &&
                    $element->carclass_id == $request->carclass_id){
                        return back()->with('flash_error',  trans('admin.Car Model already Exists'));
                    }
                }
                $CarModel = CarModel::create($Car_Model);
                return back()->with('flash_success',trans('admin.createMessageSuccess'));
            } catch (Exception $e) {
                return back()->with('flash_error',trans('admin.Something Went Wrong'));
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return CarModel::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){        
        try {
            CarModel::find($id)->delete();
            return back()->with('flash_success', trans('admin.deleteMessageSuccess'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }
}