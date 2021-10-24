<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProviderResources\ProfileController;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //
        ProfileController::checkData();
        $records = Permission::all();
        return view('admin.permissions.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        //
        return view('admin.permissions.create');
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
//        dd($request->all());
        $this->validate($request, [
            'name' => 'required|string|unique:permissions,name',
            'name_en' => 'required|string|unique:permissions,name_en',
            'routes' => 'required|string|unique:permissions,routes',
        ]);

        $record = Permission::create([
            'name'=>$request->get('name'),
            'name_en'=>$request->get('name_en'),
            'routes'=>$request->get('routes'),
        ]);
        flash()->success(trans('admin.createMessageSuccess'));
        return redirect()->back(); // route('admin.permission.index')

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
        $model = Permission::findOrFail($id);
        return view('admin.permissions.edit', compact('model'));
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
        $records = Permission::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string',
            'name_en' => 'required|string',
            'routes' => 'required|string',
        ]);

        $records->update([
            'name'=>$request->get('name'),
            'name_en'=>$request->get('name_en'),
            'routes'=>$request->get('routes'),
        ]);
        flash()->error(trans('admin.editMessageError'));
        return redirect(route('admin.permission.index'));
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
        $record = Permission::findOrFail($id);
        $record->delete();
        flash()->success(trans('admin.deleteMessageSuccess'));
        return back();
    }
}
