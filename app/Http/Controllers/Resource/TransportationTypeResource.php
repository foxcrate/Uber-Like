<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Setting;
use Illuminate\Support\Facades\File;
use Exception;
use App\Helpers\Helper;

use App\TransportationType;

class TransportationTypeResource extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $TransportationTypes = TransportationType::all();
        if($request->ajax()) {
            return $TransportationTypes;
        } else {
            return view('admin.transtype.index', compact('TransportationTypes'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $Transportation = $request->all();
        if($request->id){
            $this->validate($request, [
//                'name' => "required|max:255|unique:transportation_types,name,{$request->id}",
//                'required|unique:transportation_types,email,' . $request->id,
                'name' => "required|max:255|unique:transportation_types,name,{$request->id}",
                'name_en' => "required|max:255|unique:transportation_types,name_en,{$request->id}",
                'capacity' => 'required|numeric',
                'image' => 'mimes:jpeg,png,icon'
            ]);
            try {

                $TransportationType = TransportationType::findOrFail($request->id);

                if($request->hasFile('image')) {
                    // Helper::delete_picture($TransportationType->image);
                    // $Transportation['image'] = Helper::upload_picture($request->image);

                    if(File::exists($TransportationType->image)) {
                        File::delete($TransportationType->image);
                    }
                    $picture = $request->image;
                    $code = rand(111111111, 999999999);
                    $avatar_new_name=time().$code ."pp";
                    $picture->move('uploads/', $avatar_new_name);
                    $Transportation['image'] ='uploads/' . $avatar_new_name;
                }
                $TransportationType->update($Transportation);




                return back()->with('flash_success',trans('admin.createMessageSuccess'));
            } catch (Exception $e) {
                return back()->with('flash_error', trans('admin.Something Went Wrong'));
            }
        }else{
            $this->validate($request, [
                'name' => 'required|max:255|unique:transportation_types,name',
                'name_en' => 'required|max:255|unique:transportation_types,name_en',
                'capacity' => 'required|numeric',
                'image' => 'required|mimes:jpeg,png,icon'
            ]);
            try {
                //$Transportation['image'] = Helper::upload_picture($request->image);
                $picture = $request->image;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."pp";
                $picture->move('uploads/', $avatar_new_name);
                $Transportation['image'] ='uploads/' . $avatar_new_name;

                $TransportationType = TransportationType::create($Transportation);
                return back()->with('flash_success',trans('admin.createMessageSuccess'));
            } catch (Exception $e) {
                return back()->with('flash_error', trans('admin.Something Went Wrong'));
            }
        }

    }

    public function update(Request $request){}

    /**
     * Display the specified resource.
     *
     * @param  \App\ServiceType  $serviceType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return TransportationType::findOrFail($id);
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
            $TransportationType = TransportationType::findOrFail($id);
            //Helper::delete_picture($TransportationType->image);
            if(File::exists($TransportationType->image)) {
                File::delete($TransportationType->image);
            }
            $TransportationType->delete();
            return back()->with('flash_success', trans('admin.deleteMessageSuccess'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }
    }
    public function transtypes(){
        return TransportationType::where(['status' => 1])->get();
    }
}

// if(File::exists($TransportationType->image)) {
//     File::delete($TransportationType->image);
// }
// $picture = $request->image;
// $code = rand(111111111, 999999999);
// $avatar_new_name=time().$code ."pp";
// $picture->move('uploads/', $avatar_new_name);
// $Transportation['image'] ='uploads/' . $avatar_new_name;
