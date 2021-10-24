<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //
        $records = Role::paginate(20);
        return view('admin.roles.index', compact('records'));
    }

    public function search(Request $request)
    {
//        dd(User::with('roles')->toArray('name')->get());
        $records = Role::where(function ($role) use ($request) {
            if ($request->input('search')) {
                $role->where(function ($roles) use ($request) {
                    $roles->where('name', 'like', '%' . $request->search . '%');
                });
            }
        })->latest()->paginate(30);

        return view('admin.roles.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        //
        return view('admin.roles.create');
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
            'name' => 'required|string|unique:roles,name',
            'name_en' => 'required|string|unique:roles,name_en',
            'permissions_list' => 'required',
        ]);

        $record = Role::create([
            'name'=>$request->get('name'),
            'name_en'=>$request->get('name_en'),
        ]);
        $record->givePermissionTo((array)$request->permissions_list);
        flash()->success(trans('admin.createMessageSuccess'));
        return redirect(route('admin.role.index'));

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
        $model = Role::findOrFail($id);
        $permissions = $model->permissions()->pluck( 'permissions.name')->toArray();
        return view('admin.roles.edit', compact('model', 'permissions'));
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
//            $records->update($request->all());
//            $records->permissions()->sync((array) $request->input('permissions_list'));
////        $records->permissions()->sync($request->permissions_list);

        $records = Role::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string',
            'name_en' => 'required|string',
            'permissions_list' => 'required',
        ]);

        $records->update([
            'name'=>$request->get('name'),
            'name_en'=>$request->get('name_en'),
        ]);
        $records->syncPermissions((array)$request->permissions_list);
//        dd($request->permissions_list);
//        $model
//        $records->permissions()->sync($request->permissions_list);
        flash()->error(trans('admin.editMessageError'));
        return redirect(route('admin.role.index'));
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
        $record = Role::findOrFail($id);
        $record->delete();
        flash()->success(trans('admin.deleteMessageSuccess'));
        return back();
    }
}
