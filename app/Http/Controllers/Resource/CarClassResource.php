<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

use Setting;
use Exception;
use App\Helpers\Helper;

use App\CarClass;

class CarClassResource extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $CarClasses = CarClass::orderBy('created_at', 'desc')->get();
        if($request->ajax()) {
            return $CarClasses;
        } else {
            return view('admin.carclass.index', compact('CarClasses'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $Car_Class = $request->all();
        if($request->id){
            $this->validate($request, [
                'name' => "required|max:255|unique:car_classes,name,{$request->id}",
                'name_en' => "required|max:255|unique:car_classes,name_en,{$request->id}",
                'logo' => 'mimes:jpeg,png,icon'
            ]);
            try {
                $CarClass = CarClass::findOrFail($request->id);
                if($request->hasFile('logo')) {
                    //if($CarClass->logo) { Helper::delete_picture($CarClass->logo);}
                    //$Car_Class['logo'] = Helper::upload_picture($request->logo);

                    if(File::exists($CarClass->logo)) {
                        File::delete($CarClass->logo);
                    }
                    $picture = $request->logo;
                    $code = rand(111111111, 999999999);
                    $avatar_new_name=time().$code ."l";
                    $picture->move('uploads/', $avatar_new_name);
                    $Car_Class['logo'] ='uploads/' . $avatar_new_name;

                }
                $CarClass->update($Car_Class);
                return back()->with('flash_success',trans('admin.editMessageSuccess'));
            } catch (Exception $e) {
                return back()->with('flash_error', trans('admin.Something Went Wrong'));
            }
        }else{
            $this->validate($request, [
                'name' => 'required|max:255|unique:car_classes,name',
                'name_en' => 'required|max:255|unique:car_classes,name_en',
                'logo' => 'required|mimes:jpeg,png,icon'
            ]);
//            dd($request->all());
            try {
                //$Car_Class['logo'] = Helper::upload_picture($request->logo);
                $picture = $request->logo;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."l";
                $picture->move('uploads/', $avatar_new_name);
                $Car_Class['logo'] ='uploads/' . $avatar_new_name;

                $CarClass = CarClass::create($Car_Class);
                return back()->with('flash_success',trans('admin.createMessageSuccess'));
            } catch (Exception $e) {
                return back()->with('flash_error', trans('admin.Something Went Wrong'));
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
            return CarClass::findOrFail($id);
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
            $CarClass = CarClass::findOrFail($id);
            //Helper::delete_picture($CarClass->logo);
            if(File::exists($CarClass->logo)) {
                File::delete($CarClass->logo);
            }
            $CarClass->delete();
            return back()->with('flash_success', trans('admin.deleteMessageSuccess'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }
    }
    public function CarClasses(){
        return CarClass::where(['status' => 1])->get();
    }
}
