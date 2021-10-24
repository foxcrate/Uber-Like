<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use Setting;
use Exception;
use App\Helpers\Helper;
use Illuminate\Support\Facades\File;

use App\Box;

class BoxResource extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return Box[]|\Illuminate\Contracts\View\Factory|\Illuminate\Database\Eloquent\Collection|\Illuminate\View\View
     */
    public function index(Request $request){
        $box = Box::orderBy('created_at', 'desc')->get();
        if($request->ajax()) {
            return $box;
        } else {
            return view('admin.websiteposts.index', compact('box'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request){
        $Car_Class = $request->all();
        if($request->id){
            $this->validate($request, [
                'title' => "required|max:255",
                'title_en' => "required|max:255",
                'details' => "required|max:255",
                'details_en' => "required|max:255",
                'link' => "required|max:255",
                'photo' => 'mimes:jpeg,png,icon'
            ]);
            try {
                $box = Box::findOrFail($request->id);
                if($request->hasFile('photo')) {
                    // if($box->photo) { Helper::delete_picture($box->photo);}
                    // $Car_Class['photo'] = Helper::upload_picture($request->photo);

                    if(File::exists($box->photo)) {
                        File::delete($box->photo);
                    }
                    $picture = $request->photo;
                    $code = rand(111111111, 999999999);
                    $avatar_new_name=time().$code ."p";
                    $picture->move('uploads/', $avatar_new_name);
                    $Car_Class['photo'] ='uploads/' . $avatar_new_name;
                }
                $box->update($Car_Class);
                return back()->with('flash_success',trans('admin.editMessageSuccess'));
            } catch (Exception $e) {
                return back()->with('flash_error', trans('admin.Something Went Wrong'));
            }
        }else{
            $this->validate($request, [
                'title' => "required|max:255",
                'title_en' => "required|max:255",
                'details' => "required|max:255",
                'details_en' => "required|max:255",
                'link' => "required|max:255",
                'photo' => 'mimes:jpeg,png,icon'
            ]);
//            dd($request->all());
            try {
                //$Car_Class['photo'] = Helper::upload_picture($request->photo);
                $picture = $request->photo;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."p";
                $picture->move('uploads/', $avatar_new_name);
                $Car_Class['photo'] ='uploads/' . $avatar_new_name;

                $box = Box::create($Car_Class);
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
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            return Box::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\ServiceType $serviceType
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function destroy($id){
        try {
            $box = Box::findOrFail($id);
            //Helper::delete_picture($box->photo);
            if(File::exists($box->photo)) {
                File::delete($box->photo);
            }
            $box->delete();
            return back()->with('flash_success', trans('admin.deleteMessageSuccess'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }
    }
    public function CarClasses(){
        return Box::where(['status' => 1])->get();
    }
}
