<?php

namespace App\Http\Controllers\AuthUser;

use App\Car;
use App\CarProvider;
use App\CompanySubscription;
use App\EmailUser;
use App\Http\Controllers\ProviderResources\ProfileController;
use Cookie;
use Session;
use DB;
use Config;
use App\Mail\EilbazResetPasswordUser;
use App\Mail\UserAccountConfirmation;
use App\MobileUser;
use App\Promocode;
use App\Provider;
use App\Revenue;
use App\User;
use App\Admin;
use App\UserRequests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Nexmo\Laravel\Facade\Nexmo;
use Stevebauman\Location\Facades\Location;
use GeoIP;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;



class UserController extends Controller
{


    public function test(Request $request){
        dd($request->first_name);
        return("Aloo Aloo");
    }

    public function test2(){

        // DB::statement("ALTER TABLE user_requests CHANGE COLUMN status status ENUM('SEARCHING', 'CANCELLED','ACCEPTED', 'STARTED','ARRIVED','PICKEDUP','DROPPED','PAID','COMPLETED','SCHEDULED') NOT NULL DEFAULT 'CANCELLED'");
        // dd("Done");

        if(File::exists("uploads/user/1615728250")) {
            File::delete("uploads/user/1615728250");
        }
        return "Done";

    }


    //


    public function login(Request $request)
    {
        $rememberme = request('rememberme') == 1 ? true : false;
        if (auth()->guard('web')->attempt(['email' => request('email'), 'password' => request('password')], $rememberme)) {
            // if (auth('web')->user()->otp != 0) {
            //     flash()->error(trans('user.The account must be confirmed first') . '<a href="' . route('user.auth.accountConfirmation', [auth('web')->user()->id,auth('web')->user()->id_url]) . '" class="btn btn-xs btn-primary" style="margin-left: 20px;">' . trans('user.Account Confirmation') . '</a>');
            //     auth()->guard('web')->logout();
            //     return redirect()->route('login');
            //}
        ////Auth::logoutOtherDevices($request->password);

            return redirect()->route('index');
        }elseif(auth()->guard('web')->attempt(['mobile' => '+2'.request('email'), 'password' => request('password')], $rememberme)) {
            // if (auth('web')->user()->otp != 0) {
            //     flash()->error(trans('user.The account must be confirmed first') . '<a href="' . route('user.auth.accountConfirmation', [auth('web')->user()->id,auth('web')->user()->id_url]) . '" class="btn btn-xs btn-primary" style="margin-left: 20px;">' . trans('user.Account Confirmation') . '</a>');
            //     auth()->guard('web')->logout();
            //     return redirect()->route('login');
            // }

            ////$request->session()->flush();

            return redirect()->route('index');
        }else {
            flash()->error(trans('user.Email or password incorrectly'));
            return back();
        }
    }

    public function getRegisterUser()
    {

        // config(['mail.username' => 'America/Chicago']);
        // $x=config('mail.username');
        // //$x=$_ENV;
        // dd($x);
        //dd(Admin::find(28)->email);
        return view('user.auth.register');
    }


    public function register(Request $request)
    {
        //dd($request);
        //$request->phone_number=$request['country_code'] . $request['phone_number'];
        //dd($request->phone_number);
        $this->validate($request, [
            'first_name' => 'required|min:3|max:255',
            'last_name' => 'required|min:3|max:255',
            'phone_number' => 'required|min:11|max:11|regex:/(01)[0-9]{9}/',
            'country_code' => 'required',
            'email' => 'email|max:255|',
            'password' => 'required|min:6',
        ],[
            //'phone_number.regex' => trans('user.phone_number_regex'),
        ]);
        // dd($request['country_code'] . $request['phone_number']);
        $users = MobileUser::where('mobile', '=', $request['country_code'] . $request['phone_number'])->first();

        if ($users != null) {
            flash()->error(trans('user.The phone is already in use'));
            //flash()->error("Aloo");
            return redirect()->back();
            //return "Alo phone";
        }

        $emailUsers = EmailUser::where('email', '=', $request['email'])->first();

        if ($emailUsers != null) {
            flash()->error(trans('user.The email is already in use'));
            //flash()->error("Aloo");
            return redirect()->back();
            //return "Alo email";
        }

        //////////////////////////////////////////////////////////////////////////////////
        //dd($request->first_name);

        return view('user.auth.phoneConfirmation', ['user' => $request]);


    }

    public function register2(Request $request){
        //dd($request);
        $request->merge(['password' => bcrypt($request->password)]);

        //dd($request->last_name);
        $request->merge(['first_name' => base64_encode($request->first_name)]);
        $request->merge(['last_name' => base64_encode($request->last_name)]);
        //dd(base64_decode($request->last_name));

        //dd("ALoo");
        $user = User::create($request->all());
        //dd($user);

        $user->mobile = $request['country_code'] . $request['phone_number'];
        $user->payment_mode = 'CASH';
        $user->id_url = str_random(100);

        $user->register_from="web";

        $user->save();

        $emailUser = EmailUser::create([
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        $mobileUser = MobileUser::create([
            'user_id' => $user->id,
            'mobile' => $user->mobile
        ]);

        if ($user) {

            $code = rand(111111, 999999);
            $ids = $user->id;
            $update = $user->update(['otp' => $code]);

            if ($update) {
                try {

                    $admin=Admin::find(28);
                    config(['mail.username' => $admin->email]);
                    config(['mail.password'=>$admin->email_password]);
                    //dd( $admin->email, $admin->email_password);

                    Mail::to($user->email)
                    //->bcc('ailbaza156@gmail.com')
                    //->bcc(Admin::find(28)->email)
                    ->send(new UserAccountConfirmation($code, $ids));

                   /* Nexmo::message()->send([
                        'to' =>  $user->mobile,
                        'from' => '+201146469865',
                        'text' => $code
                    ]);*/

                   //return redirect(route('user.auth.accountConfirmation', [$ids, $user->id_url]))->with('flash_success', trans('admin.createMessageSuccess'));
                   return redirect(route('login'));

                }catch (Exception $e) {
                   // $eror_mail=trans('admin.Something Went Wrong');
                    //return $user;
                   // $user_drob = User::find($ids);


                    //return view('user.auth.register', ['eror_mail' => trans('admin.Something Went Wrong mail')]);
                    return redirect(route('login'));
               }
                  } else {

                return back()->with('flash_error', trans('admin.Something Went Wrong'));
            }
        } else {

            return back()->with('flash_error', trans('admin.User Not Found'));
        }

    }

    public function checkPhone(Request $request){
        response()->json("alo")->send();
        //die();
        //redirect("/");
        // return response()->json([
        //     'redirect' => url('/')
        // ]);
        // return redirect("/");
        // dd($request['email']);
        // return $request;
    }


    public function userCheck($id, $token)
    {
        $user = User::findOrFail($id);
        if ($user->id_url == $token) {
            return view('user.auth.accountConfirmation', compact('user'));
        }
        return abort(404);
    }


    public function check_account(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->validate($request, [
            'otp' => 'required',
        ]);

        $users = $user->where('otp', $request->otp)->where('otp', '!=', 0)
            ->first();
        if ($users) {
//            dd($users['otp']);
            $user->otp = 0;

            if ($user->save()) {
                flash()->success(trans('user.The account was successfully confirmed'));
                return redirect()->route('login');
            } else {
                return back()->with('error', 'Wrong email Or Code Details');
            }
        } else {
            flash()->error(trans('user.The code you entered is not valid'));
            return back()->with('error', 'Wrong email Or Code Details');
        }
    }

    public function resend_code(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user) {
            $code = rand(111111, 999999);
            $ids = $user->id;
            $update = $user->update(['otp' => $code]);
            if ($update) {
//                dd($user->id);
                // Mail::to($user->email)
                //     ->bcc(Admin::find(28)->email)
                //     ->send(new UserAccountConfirmation($code, $ids));

                $admin=Admin::find(28);
                    config(['mail.username' => $admin->email]);
                    config(['mail.password'=>$admin->email_password]);
                    //dd( $admin->email, $admin->email_password);

                    Mail::to($user->email)
                    ->bcc('ailbaza156@gmail.com')
                    //->bcc(Admin::find(28)->email)
                    ->send(new UserAccountConfirmation($code, $ids));

                flash()->success(trans('user.sent successfully'));
                return redirect(route('user.auth.accountConfirmation', $user->id))->with('flash_success', trans('admin.createMessageSuccess'));
            } else {
                return back()->with('flash_error', trans('admin.Something Went Wrong'));
            }
        } else {
            return back()->with('flash_error', trans('admin.User Not Found'));
        }
    }

    public function resetUser()
    {
        return view('user.auth.passwords.email');
    }

    public function resetPasswordUser(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $code = rand(111111, 999999);
            $update = $user->update(['code_reset' => $code]);
//            dd($user->update(['otp' => $code])) ;
            if ($update) {

                // Mail::to($user->email)
                //     ->bcc('ailbaza156@gmail.com')
                //     ->send(new EilbazResetPasswordUser($code, $user->id));

                $admin=Admin::find(28);
                    config(['mail.username' => $admin->email]);
                    config(['mail.password'=>$admin->email_password]);
                    //dd( $admin->email, $admin->email_password);

                    Mail::to($user->email)
                    ->bcc('ailbaza156@gmail.com')
                    //->bcc(Admin::find(28)->email)
                    ->send(new EilbazResetPasswordUser($code, $user->id));

                flash()->success(trans('user.sent successfully'));
                return redirect()->back();
            } else {
                return back()->with('flash_error', trans('admin.Something Went Wrong'));
            }
        } else {
            return back()->with('flash_error', trans('admin.Something Went Wrong'));
        }

    }

    public function newPasswordUser($id, $code)
    {
        $user = User::where('code_reset', $code)->where('code_reset', '!=', 0)->findOrFail($id);
        $user_code = $user->code_reset;
        $user_id = $user->id;
        return view('user.auth.passwords.reset', compact('user_code', 'user_id'));
    }

    public function postNewPasswordUser(Request $request, $id, $code)
    {
        $this->validate($request, [
            'password' => 'required|confirmed',
        ]);

//        $user = User::where('otp',$request->otp)->where('otp', '!=' , 0)
//            ->where('email',$request->email)->first();
        $user = User::where('code_reset', $code)->where('code_reset', '!=', 0)->findOrFail($id);

        if ($user) {
            $user->password = bcrypt($request->password);
            $user->code_reset = 0;

            if ($user->save()) {
                flash()->success(trans('user.sent successfully'));
                return redirect()->route('index');
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
        if (auth('web')->check()) {
            auth()->guard('web')->logout();

            $x=Cookie::forget('XSRF-TOKEN');
            $var=Cookie::get('XSRF-TOKEN');
            $cookie1 = Cookie::make('XSRF-TOKEN', 'no', 120);
            $var=Cookie::get('XSRF-TOKEN');
            //return response()->withCookie($cookie1);

            //dd($cookie);
            Session::flush();
            return redirect()->route('login');

        }

        return redirect()->back();
    }

    public function getLogin(Request $request)
    {

       /*  //$ip = request()->ip();
         $ip= $request->ip();
        // $ip = request()->ip();
        //return geoip()->getLocation('27.974.399.65');
        ProfileController::checkData();
        //$ip = request()->ip();
        $data = Location::get($ip);
        dd($data);*/
        ProfileController::checkData();

        $var=Cookie::get('XSRF-TOKEN');
        //dd($var);

        return view('user.auth.login');
    }

}
