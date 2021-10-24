<?php

namespace App\Http\Controllers;

use App\Governorate;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //
        $records = Governorate::all();
        return view('admin.governorates.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        //
        return view('admin.governorates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'name' =>'required',
            'name_en' =>'required'
        ]);
//        $record = new Governorate;
//        $record->name = $request->input('name');
//        $record->save();
        $record = Governorate::create($request->all());
        flash()->success(trans('admin.createMessageSuccess'));
        return redirect()->back(); //route('admin.governorate.index')
    }

    public function changeStatus(Request $request)
    {
        //dd("Alo");
        //return request();
        $g=Governorate::find($request->governorate_id);
        //dd($g);
        if($g->available==0){
            $g->available=1;
        }else if($g->available==1){
            $g->available=0;
        }

        $g->save();
        //return $g;
        // $servicess = Governorate::all();
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = Governorate::findOrFail($id);
        return view('admin.governorates.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        //
        $records = Governorate::findOrFail($id);
        $records->update($request->all());
        flash()->success(trans('admin.editMessageSuccess'));
        return redirect(route('admin.governorate.index'));
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $record = Governorate::findOrFail($id);
        $record->delete();
        flash()->success(trans('admin.deleteMessageSuccess'));
        return back();
    }
}
