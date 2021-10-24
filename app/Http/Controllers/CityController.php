<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $records = City::paginate(20);
        return view('admin.cities.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        //
        $governorates = DB::table('governorates')->get();
        return view('admin.cities.create', compact('governorates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'name' => 'required',
            'name_en' => 'required',
            'governorate_id' => 'required|exists:governorates,id',
        ]);

        $record = City::create($request->all());
        flash()->success(trans('admin.createMessageSuccess'));
        return redirect(route('admin.city.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = City::findOrFail($id);
        $governorates = DB::table('governorates')->get();
        return view('admin.cities.edit', compact('model', 'governorates'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        //
        $records = City::findOrFail($id);
        $records->update($request->all());
        flash()->success(trans('admin.editMessageSuccess'));
        return redirect(route('admin.city.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $record = City::findOrFail($id);
        $record->delete();
        flash()->success(trans('admin.deleteMessageSuccess'));
        return back();
    }
}