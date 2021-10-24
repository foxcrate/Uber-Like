<?php

namespace App\Http\Controllers\AdminAuth;

use App\CompanySubscription;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProviderResources\ProfileController;
use App\Promocode;
use App\Revenue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanySubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //
        $records = CompanySubscription::paginate(20);
        ProfileController::checkData();
        return view('admin.companysubscriptions.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $fleet = DB::table('fleets')->get();
        return view('admin.companysubscriptions.create', compact('fleet'));
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
            'fleet_id' => 'required|exists:fleets,id',
            'money' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);
//        dd($request->from < $request->to);

        if ($request->from < $request->to && date("Y-m-d") >= $request->from) {

            $record = CompanySubscription::create([
                'fleet_id' => $request->get('fleet_id'),
                'money' => $request->get('money'),
                'from' => $request->get('from'),
                'to' => $request->get('to'),
                'status' => 'active'
            ]);
            flash()->success(trans('admin.createMessageSuccess'));
            return redirect(route('admin.company-subscription.index'));

        } elseif ($request->from < $request->to && date("Y-m-d") != $request->from) {
            $record = CompanySubscription::create([
                'fleet_id' => $request->get('fleet_id'),
                'money' => $request->get('money'),
                'from' => $request->get('from'),
                'to' => $request->get('to'),
                'status' => 'not_active'
            ]);
            flash()->success(trans('admin.createMessageSuccess'));
            return redirect(route('admin.company-subscription.index'));
        } else {
            flash()->error(trans('admin.Date From > Date To'));
            return redirect()->back();
        }
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
        $model = CompanySubscription::findOrFail($id);
        $fleet = DB::table('fleets')->get();
        return view('admin.companysubscriptions.edit', compact('model', 'fleet'));
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
        $records = CompanySubscription::findOrFail($id);
        $this->validate($request, [
            'fleet_id' => 'required|exists:fleets,id',
            'money' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);
        if ($request->from < $request->to && date("Y-m-d") >= $request->from) {

            $records->update([
                'fleet_id' => $request->get('fleet_id'),
                'money' => $request->get('money'),
                'from' => $request->get('from'),
                'to' => $request->get('to'),
                'status' => 'active'
            ]);
            flash()->success(trans('admin.editMessageSuccess'));
            return redirect(route('admin.company-subscription.index'));

        } elseif ($request->from < $request->to && date("Y-m-d") != $request->from) {

            $records->update([
                'fleet_id' => $request->get('fleet_id'),
                'money' => $request->get('money'),
                'from' => $request->get('from'),
                'to' => $request->get('to'),
                'status' => 'not_active'
            ]);
            flash()->success(trans('admin.editMessageSuccess'));
            return redirect(route('admin.company-subscription.index'));

        } else {
            $records->status = 'time_finish';
            $records->save();
            flash()->error(trans('admin.Date From > Date To'));
            return redirect()->back();
        }
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
        $record = CompanySubscription::findOrFail($id);
        $record->delete();
        flash()->success(trans('admin.deleteMessageSuccess'));
        return back();
    }
}
