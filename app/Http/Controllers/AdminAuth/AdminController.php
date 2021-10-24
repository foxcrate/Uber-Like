<?php

namespace App\Http\Controllers\AdminAuth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Admin;
use App\Http\Controllers\ProviderResources\ProfileController;
use Illuminate\Contracts\View\Factory as FactoryAlias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return FactoryAlias|\Illuminate\View\View
     */
    public function index()
    {
        ProfileController::checkData();
        $records = Admin::paginate(20);
        return view('admin.admins.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return FactoryAlias|\Illuminate\View\View
     */
    public function create()
    {
        //
        return view('admin.admins.create');
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
            'name' => 'required',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|confirmed|min:6',
            'picture' => 'required|image|mimes:jpg,jpeg,png,bmp,gif,svg,jfif',
            'roles_list' => 'nullable'
        ]);

        $request->merge(['password' => bcrypt($request->password)]);
        $admin = Admin::create($request->all());
        $admin->syncRoles((array)$request->roles_list);
//        if ($request->has('roles_list') and count((array)$request->get('roles_list')) > 0) {
//            $permissions = [];
//            foreach ((array)$request->get('roles_list') as $role) {
//            $_role = Role::findById($role);
//            if ($_role) {
//                $currents = $_role->permissions()->pluck('name')->toArray();
//                $permissions = array_merge($permissions, $currents);
//            }
//        }
//            $permissions = array_values($permissions);
//            $admin->syncPermissions($permissions);
//        }

        if ($request->hasFile('picture')) {
            $picture = $request->picture;
            $code = rand(111111111, 999999999);
            $picture_new_name = time() . $code ."pp";
            $picture->move('uploads/admin', $picture_new_name);

            $admin->picture = 'uploads/admin/' . $picture_new_name;
            $admin->save();
        }

        flash()->success(trans('admin.createMessageSuccess'));
        return redirect(route('admin.admin.index'));

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
        $model = Admin::findOrFail($id);
//        $roles = $model->roles()->pluck( 'roles.name')->toArray();

        return view('admin.admins.edit', compact('model', 'roles'));
    }


    public
    function update(Request $request, $id)
    {

        $records = Admin::findOrFail($id);
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:admins,email,' . $id,
            'password' => 'sometimes|nullable|confirmed|min:6',
            'picture' => 'nullable|image|mimes:jpg,jpeg,png,bmp,gif,svg,jfif',
            'roles_list' => 'nullable',
        ]);
        $records->syncRoles((array)$request->roles_list);
//        if ($request->has('roles_list') and count((array)$request->get('roles_list')) > 0) {
//            $permissions = [];
//
//            foreach ((array)$request->get('roles_list') as $role) {
//                $_role = Role::findById($role);
//                if ($_role) {
//                    $currents = $_role->permissions()->pluck('name')->toArray();
//                    $permissions = array_merge($permissions, $currents);
//                }
//            }
//            $permissions = array_values($permissions);
//            $records->syncPermissions($permissions);

//        }


        $records->update($request->except('password'));
        if ($request->hasFile('picture')) {
            $picture = $request->picture;
            $code = rand(111111111, 999999999);
            $picture_new_name = time() . $code."pp";
            $picture->move('uploads/admin', $picture_new_name);

            $records->picture = 'uploads/admin/' . $picture_new_name;
            $records->save();
        }

        if (request()->input('password')) {
            $records->update(['password' => bcrypt($request->password)]);
        }

        flash()->success(trans('admin.editMessageSuccess'));
        return redirect(route('admin.admin.index'));

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
        $record = Admin::findOrFail($id);
        $record->delete();
        flash()->success(trans('admin.deleteMessageSuccess'));
        return back();
    }

}
