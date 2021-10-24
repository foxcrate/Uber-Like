<?php

namespace App\Http\Controllers\Resource;

use App\Governorate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Setting;
use Exception;
use Illuminate\Support\Facades\File;
use App\Helpers\Helper;

use App\ServiceType;
use App\TransportationType;

class ServiceResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return ServiceType[]|\Illuminate\Contracts\View\Factory|\Illuminate\Database\Eloquent\Collection|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $services = ServiceType::all();
        $transportation_types = DB::table('transportation_types')->where('status','1')->get();
        if($request->ajax()) {
            return $services;

        } else {
            return view('admin.service.index', compact('services', 'transportation_types'));
        }
    }
    public function changeStatus(Request $request)
    {
        // dd("Alo");
        // $g=Governorate::find(6);
        // $g->available=0;
        // $g->save();

        // $servicess = ServiceType::all();
        // $services = ServiceType::findOrFail($_POST['service_id']);
        // if($request->ajax()) {
        //     $services->status=$_POST['status'];
        //     $services->save();
        //     return $services;
        //     dd("Done");
        // } else {
        //     return view('admin.service.index', compact('servicess'));
        // }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {

        return view('admin.service.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request){
        $Service = $request->all();
        if($request->id){
            $this->validate($request, [
                'name' => "required|max:255",
                'name_en' => "required|max:255",
                'image' => 'mimes:jpeg,png,icon',
                'description' => 'required|max:255',
                'fixed' => 'required|numeric',
                'price' => 'required|numeric',
                'minute' => 'required|numeric',
                'distance' => 'required|numeric',
                'sub_com' => 'required|numeric',
                'transportation_type_id' => 'required|exists:transportation_types,id',
                'calculator' => 'required|in:MIN,HOUR,DISTANCE,DISTANCEMIN,DISTANCEHOUR'
            ]);

            try {
                $ServiceType = ServiceType::findOrFail($request->id);
                if($request->hasFile('image')) {
                    //Helper::delete_picture($ServiceType->image);
                    //$Service['image'] = Helper::upload_picture($request->image);

                    if(File::exists($ServiceType->image)) {
                        File::delete($ServiceType->image);
                    }
                    $picture = $request->image;
                    $code = rand(111111111, 999999999);
                    $avatar_new_name=time().$code ."pp";
                    $picture->move('uploads/', $avatar_new_name);
                    $Service['image'] ='uploads/' . $avatar_new_name;
                }
                $ServiceType->update($Service);
                return redirect()->route('admin.service.index')->with('flash_success', trans('admin.editMessageSuccess'));
            } catch (Exception $e) {
                return back()->with('flash_error', trans('admin.Something Went Wrong'));
            }
        }else{
            $this->validate($request, [
                'name' => 'required|max:255|unique:service_types,name',
                'name_en' => 'required|max:255|unique:service_types,name_en',
                'image' => 'required|mimes:jpeg,png,icon',
                'description' => 'required|max:255',
                'fixed' => 'required|numeric',
                'price' => 'required|numeric',
                'minute' => 'required|numeric',
                'distance' => 'required|numeric',
                'sub_com' => 'required|numeric',
                'transportation_type_id' => 'required|exists:transportation_types,id',
                'calculator' => 'required|in:MIN,HOUR,DISTANCE,DISTANCEMIN,DISTANCEHOUR'
            ]);
            try {
                //$Service['image'] = Helper::upload_picture($request->image);

                $picture = $request->image;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."pp";
                $picture->move('uploads/', $avatar_new_name);
                $Service['image'] ='uploads/' . $avatar_new_name;

                $ServiceType = ServiceType::create($Service);
                return redirect()->route('admin.service.index')->with('flash_success', trans('admin.createMessageSuccess'));
            } catch (ModelNotFoundException $e) {
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
    public function show($id){
        try {
            return ServiceType::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ServiceType  $serviceType
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $service = ServiceType::findOrFail($id);
            return view('admin.service.edit',compact('service'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        try{
            $ServiceType = ServiceType::findOrFail($id);
            //Helper::delete_picture($ServiceType->image);
            if(File::exists($ServiceType->image)) {
                File::delete($ServiceType->image);
            }
            $ServiceType->delete();
            return back()->with('message', trans('admin.deleteMessageSuccess'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }
    }
    public function services(){
        return ServiceType::where(['status' => 1])->get();
    }





//
//
//    public function frontend(Request $request)
//    {
//        $services = ServiceType::findOrFail($request->id);
//        return view('public.services.index', compact('services'));
//
//    }

    public function servicesTransportation(Request $request,$id)
    {
        $transportationType = TransportationType::whereId($id)->get()->first();
        return view('public.services.index', compact('transportationType'));

    }

}
