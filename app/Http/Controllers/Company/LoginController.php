<?php

namespace App\Http\Controllers\Company;

use App\Fleet;
use App\Http\Controllers\ProviderResources\ProfileController;
use App\Mail\EilbazResetPasswordCompany;
use App\Mail\EilbazResetPasswordUser;
use App\Mail\UserAccountConfirmation;
use App\User;
use App\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    //

    public function getLogin()
    {
        ProfileController::checkData();
        return view('company.auth.login');
    }

    public function login(Request $request)
    {
        //return 'Aloo';
        //auth()->guard('fleet')->logout();
        $rememberme = request('rememberme') == 1 ? true : false;
//        dd(auth()->guard('fleet'));
        if (auth()->guard('fleet')->attempt(['email' => request('email'), 'password' => request('password')], $rememberme)) {
//            dd('abracadabra');
            return redirect()->route('company.index');
        } else {
            flash()->error(trans('user.Email or password incorrectly'));
            return back();
        }
    }

    public function getEmail()
    {
        ProfileController::checkData();
        return view('company.auth.passwords.email');
    }

    public function alo(){
        return "Aloo";
    }

    public function postEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $company = Fleet::where('email', $request->email)->first();
//        $company = Fleet::find($id);
        if ($company) {
            $code = rand(1111, 9999);
            $ids = $company->id;
            $update = $company->update(['otp' => $code]);
//            dd($company->update(['otp' => $code])) ;
            if ($update) {

                // Mail::to($company->email)
                //     ->bcc(Admin::find(28)->email)
                //     ->send(new EilbazResetPasswordCompany($code, $ids));

                $admin=Admin::find(28);
                config(['mail.username' => $admin->email]);
                config(['mail.password'=>$admin->email_password]);
                //dd( $admin->email, $admin->email_password);

                Mail::to($company->email)
                ->bcc('ailbaza156@gmail.com')
                //->bcc(Admin::find(28)->email)
                ->send(new EilbazResetPasswordCompany($code, $ids));

                flash()->success(trans('company.sent successfully'));
                return redirect()->back();
            } else {
                return back()->with('flash_error', trans('admin.Something Went Wrong'));
            }
        } else {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }

    }

    public function reset($id)
    {
        $company = Fleet::find($id);
        return view('company.auth.passwords.reset', compact('company'));
    }

    public function resetPassword(Request $request, $id)
    {
        $this->validate($request, [
            'otp' => 'required',
            'password' => 'required|confirmed',
        ]);

//        $company = Fleet::where('otp',$request->otp)->where('otp', '!=' , 0)->find($id);
        $company = Fleet::find($id);
        if ($company->otp != 0) {
            if ($company->otp == $request->otp) {
                $company->password = bcrypt($request->password);
                $company->otp = 0;

                if ($company->save()) {
                    flash()->success(trans('user.sent successfully'));
                    return redirect()->route('company.login');
                } else {
                    flash()->error(trans('admin.Wrong email Or Code Or Password Details'));
                    return back()->with('error', 'Wrong email Or Code Or Password Details');
                }
            } else {
                flash()->error(trans('admin.Wrong email Or Code Or Password Details'));
                return back()->with('error', 'Wrong email Or Code Or Password Details');
            }
        } else {
            flash()->error(trans('admin.Wrong email Or Code Or Password Details'));
            return back()->with('error', 'Wrong email Or Code Or Password Details');
        }
    }

    public function logout()
    {
        //return 'alo';
        if (auth('fleet')->check()) {
            auth()->guard('fleet')->logout();
            return redirect()->route('company.login');
        }
        return redirect()->back();
    }

}
