<?php

namespace App\Http\Controllers\Resource;

use App\EmailProvider;
use App\EmailUser;
use App\MobileUser;
use App\User;
use App\UserRequests;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Setting;

class UserResource extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|unique:users,email|email|max:255',
            'phone_number' => 'required|min:11|max:11|regex:/(01)[0-9]{9}/',
            'country_code' => 'required',
            'picture' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
            'password' => 'required|min:6|confirmed',
        ]);

        $users = MobileUser::where('mobile', '=', $request['country_code'] . $request['phone_number'])->first();

        if ($users != null) {
            flash()->error(trans('user.The phone is already in use'));
            return redirect()->back();
        }

        $emailUsers = EmailUser::where('email', '=', $request['email'])->first();

        if ($emailUsers != null) {
            flash()->error(trans('user.The email is already in use'));
            return redirect()->back();
        }

        try {

            $user = $request->all();

            $user['payment_mode'] = 'CASH';
            $user['password'] = bcrypt($request->password);
            $user['first_name']= base64_encode($request->first_name);
            $user['last_name']= base64_encode($request->last_name);
            if ($request->hasFile('picture')) {
                $picture = $request->picture;
                $picture_new_name = time() . $picture->getClientOriginalName();
                $picture->move('user/profile', $picture_new_name);

               return $user->picture = 'user/profile/' . $picture_new_name;
                $user->save();
            }

            $user = User::create($user);
            $emailUser = EmailUser::create([
                'user_id' => $user->id,
                'email' => $request->email,
            ]);

            $mobileUser = MobileUser::create([
                'user_id' => $user->id,
                'mobile' => $request->mobile,
            ]);
            return back()->with('flash_success', trans('admin.createMessageSuccess'));

        } catch (Exception $e) {
            return back()->with('flash_error', trans('admin.User Not Found'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return view('admin.users.user-details', compact('user'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */

    public function wallet_update(Request $request)
    {
        try {
            $user = User::where(['id' => $request->user_id])->first();
            $total_cash = $user->wallet_balance + $request->cash;
            $user->wallet_balance = $total_cash;
            $user->save();
            return redirect()->back();
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
            return view('admin.users.edit', compact('user'));
        } catch (ModelNotFoundException $e) {
            return $e;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'mobile' => 'required|regex:/(01)[0-9]{9}/|unique:users,mobile,' . $id,
            'picture' => 'mimes:jpeg,jpg,bmp,png|max:5242880',
        ]);

        try {

            $user = User::findOrFail($id);

            if ($user->mobile != $request->mobile) {
                $usersMobile = MobileUser::where('mobile', '=', $request['mobile'])->first();
                if ($usersMobile != null) {
                    flash()->error(trans('user.The phone is already in use'));
                    return redirect()->back();
                }
                // $user->mobile=$request->mobile;
                // $user->save();
            }
            if ($user->email != $request->email) {
                $emailUsers = EmailUser::where('email', '=', $request['email'])->first();
                //dd($emailUsers != null);
                if ($emailUsers != null) {
                    flash()->error(trans('user.The email is already in use'));
                    return redirect()->back();
                }
                // $user->email=$request->email;
                // $user->save();
            }

            if ($request->hasFile('picture')) {
                $picture = $request->picture;
                $code = rand(111111111, 999999999);
                $picture_new_name = time() . $code."pp";
                $picture->move('uploads/user', $picture_new_name);

                $user->picture = 'uploads/user/' . $picture_new_name;
                $user->save();
            }

            $user->first_name = base64_encode($request->first_name);
            $user->last_name = base64_encode($request->last_name);
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->save();

            if ($request->email != $user->email) {
                $emailProvider = EmailUser::create([
                    'provider_id' => $user->id,
                    'email' => $user->email,
                ]);
            }

            if ($request->mobile != $user->mobile) {
                $mobileProvider = MobileUser::create([
                    'provider_id' => $user->id,
                    'mobile' => $user->mobile,
                ]);
            }

            return redirect()->route('admin.user.index')->with('flash_success', trans('admin.editMessageSuccess'));
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', trans('admin.User Not Found'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if (Setting::get('demo_mode', 0) == 1) {
            return back()->with('flash_error', trans('admin.Disabled for demo purposes! Please contact us at info@appoets.com'));
        }

        try {

            User::find($id)->delete();
            return back()->with('flash_success', trans('admin.deleteMessageSuccess'));
        } catch (Exception $e) {
            return back()->with('flash_error', trans('admin.User Not Found'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Provider $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function request($id)
    {

        try {

            $requests = UserRequests::where('user_requests.user_id', $id)
                ->RequestHistory()
                ->get();

            return view('admin.request.index', compact('requests'));
        } catch (Exception $e) {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }

    }

    public function trashed()
    {
        $user = User::onlyTrashed()->get();

        return view('admin.users.softDeleted')->with('users', $user);
    }

    public function hdelete($id)
    {
        $worker = User::withTrashed()->where('id', $id)->first();
        $worker->forceDelete();
        return redirect()->back();
    }

    public function restore($id)
    {
        $worker = User::withTrashed()->where('id', $id)->first();
        $worker->restore();
        return redirect()->route('admin.user.index');
    }

}
