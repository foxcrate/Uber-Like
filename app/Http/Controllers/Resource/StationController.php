<?php

namespace App\Http\Controllers\Resource;

use App\Car;
use App\CarModel;
use App\CarProvider;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProviderResources\ProfileController;
use App\Itinerary;
use App\Mail\EilbazProviderNotImage;
use App\Provider;
use App\ProviderService;
use App\Station;
use App\UserRequests;
use Illuminate\Contracts\View\Factory as FactoryAlias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return FactoryAlias|\Illuminate\View\View
     */
    public function index()
    {
        $records = Station::orderBy('created_at', 'desc')->get();
        return view('admin.stations.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return FactoryAlias|\Illuminate\View\View
     */
    public function create()
    {

        return view('admin.stations.create');
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
        $this->validate($request, [
            'station' => 'required',
            'substation' => 'nullable',
        ]);
        Station::create($request->all());

        flash()->success(trans('admin.createMessageSuccess'));
        return redirect(route('admin.station.index'));
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
        $model = Station::findOrFail($id);

        return view('admin.stations.edit', compact('model'));
    }

//
    public
    function update(Request $request, $id)
    {
        $records = Station::findOrFail($id);
        $this->validate($request, [
            'station' => 'required',
            'substation' => 'nullable',
        ]);
        $records->update($request->all());

        flash()->success(trans('admin.editMessageSuccess'));
        return redirect(route('admin.station.index'));
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
        $record = Station::findOrFail($id);
        $record->delete();
        flash()->success(trans('admin.deleteMessageSuccess'));
        return back();
    }
}
