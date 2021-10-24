<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\DayTripTime;
use App\Http\Controllers\ProviderResources\ProfileController;
use Illuminate\Contracts\View\Factory as FactoryAlias;
use Illuminate\Http\Request;

class DayTripTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return FactoryAlias|\Illuminate\View\View
     */
    public function index()
    {
        ProfileController::checkData();
        $records = DayTripTime::all();
        return view('admin.dayTripTimes.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return FactoryAlias|\Illuminate\View\View
     */
    public function create()
    {
        //
        return view('admin.dayTripTimes.create');
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
        //
        $this->validate($request, [
            'day' => 'required',
            'period' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);

        $dayTripTimes = DayTripTime::create($request->all());

        flash()->success(trans('admin.createMessageSuccess'));
        return redirect(route('admin.day-trip-time.index'));

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
    public
    function edit($id)
    {
        $model = DayTripTime::findOrFail($id);

        return view('admin.dayTripTimes.edit', compact('model'));
    }


    public
    function update(Request $request, $id)
    {

        $records = DayTripTime::findOrFail($id);
        $this->validate($request, [
            'day' => 'required',
            'period' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);

        $records->update($request->all());

        flash()->success(trans('admin.editMessageSuccess'));
        return redirect(route('admin.day-trip-time.index'));

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
        $record = DayTripTime::findOrFail($id);
        $record->delete();
        flash()->success(trans('admin.deleteMessageSuccess'));
        return back();
    }

    public function statusOpen($id)
    {
        $record = DayTripTime::findOrFail($id);
        $record->update(['status' => '1']);
        flash()->success(trans('admin.editMessageSuccess'));
        return back();
    }

    public function statusClose($id)
    {
        $record = DayTripTime::findOrFail($id);
        $record->update(['status' => '0']);
        flash()->success(trans('admin.editMessageSuccess'));
        return back();
    }
    public function CarClasses(){
        return DayTripTime::where(['status' => 1])->get();
    }
}