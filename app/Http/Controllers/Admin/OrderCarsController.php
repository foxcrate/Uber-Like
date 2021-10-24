<?php

namespace App\Http\Controllers\Admin;

use App\CarModel;
use App\Governorate;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CarsRequest;
use App\Mail\SendMassageToUser;
use App\Mail\SendMassageToUserNotActive;
use App\Request_car;
use App\Settings;
use App\User;
use App\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
//use File;
use Illuminate\Support\Facades\File;
class OrderCarsController extends Controller
{
    public  function index(){
        $Requests=Request_car::orderBy('id', 'DESC')->get();
        return view('admin.requestCars.index',compact('Requests'));
    }
    public  function view($id){
        try{
       return $Request=Request_car::findOrFail($id);
        return view('admin.requestCars.view',compact('Request'));
         }catch (\Exception $e){
            return redirect()->back()->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }
    public function create()
    {
        $cars_models= CarModel::select()->get();
        $users=User::where('otp','0')->get();
        $governorates=Governorate::select()->get();
        return view('admin.requestCars.create',compact("cars_models",'users','governorates'));
    }
    public function store(CarsRequest $request)
    {
        //return $request;
        try{
            $user=User::whereId($request->id_borr)->get()->first();
            if(!$user){
                return redirect()->route('admin.order.index')->with('flash_error',trans('admin.User Not Found'));
            }
            $photo_file1=null;
            $photo_file2=null;
            $photo_file3=null;
            $photo_file4=null;
            if ($request->latitude == '' or $request->latitude == null or $request->longitude == '' or $request->longitude == null){
                return redirect()->back()->with('flash_error',trans('admin.Something Went Wrong'));
            }
            if($request->hasFile('photo')) {
                //$photo_file1= Helper::upload_picture($request->file('photo'));
                $picture = $request->photo;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."p1";
                $picture->move('uploads/car/', $avatar_new_name);
                $photo_file1 ='uploads/car/' . $avatar_new_name;
            }
            if($request->hasFile('photo2')) {
                //$photo_file2 = Helper::upload_picture($request->file('photo2'));
                $picture = $request->photo2;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."p2";
                $picture->move('uploads/car/', $avatar_new_name);
                $photo_file2 ='uploads/car/' . $avatar_new_name;

            }
            if($request->hasFile('photo3')) {
                //$photo_file3 = Helper::upload_picture($request->file('photo3'));
                $picture = $request->photo3;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."p3";
                $picture->move('uploads/car/', $avatar_new_name);
                $photo_file3 ='uploads/car/' . $avatar_new_name;

            }
            if($request->hasFile('photo4')) {
                //$photo_file4 = Helper::upload_picture($request->file('photo4'));
                $picture = $request->photo4;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."p4";
                $picture->move('uploads/car/', $avatar_new_name);
                $photo_file4 ='uploads/car/' . $avatar_new_name;

            }
            $year= CarModel::whereId($request->id_models)->first()->date;
            $sePrice = Settings::where('key','pay_for_ad')->get()->first()->value;

            $to30=Carbon::now()->addDays(30);
            $to=date('Y-m-d',strtotime($to30));
            // $record= Request_car::create([
            //     'id_borr'=>$user->id,
            //     'id_models'=>$request->id_models,
            //     'id_governorate'=>$request->id_governorate,
            //     'number_seats'=>$request->number_seats,
            //     'full_type'=>$request->full_type,
            //     'gearbox'=>$request->gearbox,
            //     'color'=>$request->color,
            //     'note_ar'=>$request->note_ar,
            //     'note_en'=>$request->note_en,
            //     'price'=>$request->price,
            //     'from'=>date('Y-m-d'),
            //     'to'=>date('Y-m-d'),
            //     'photo1'=> $photo_file1,
            //     'photo2'=> $photo_file2,
            //     'photo3'=> $photo_file3,
            //     'photo4'=> $photo_file4,
            //     'year_date'=> $year,
            //     'lat'=> $request->latitude,
            //     'long'=> $request->longitude,
            // ]);
            if($user->wallet_balance >= $sePrice){


                $record= Request_car::create([
                    'id_borr'=>$user->id,
                    'id_models'=>$request->id_models,
                    'id_governorate'=>$request->id_governorate,
                    'number_seats'=>$request->number_seats,
                    'full_type'=>$request->full_type,
                    'gearbox'=>$request->gearbox,
                    'color'=>$request->color,
                    'note_ar'=>$request->note_ar,
                    'note_en'=>$request->note_en,
                    'price'=>$request->price,
                    'from'=>date('Y-m-d'),
                    'to'=>date('Y-m-d'),
                    'photo1'=> $photo_file1,
                    'photo2'=> $photo_file2,
                    'photo3'=> $photo_file3,
                    'photo4'=> $photo_file4,
                    'year_date'=> $year,
                    'lat'=> $request->latitude,
                    'long'=> $request->longitude,
                ]);

                $record->update([
                    'status'=>1,
                    'from'=>date('d-m-Y'),
                    'to'=>$to
                ]);
                $date = date('d-m-Y',strtotime($to)-1);
                $user->update([
                    'wallet_balance'=> $user->wallet_balance - $sePrice,
                ]);
                // Mail::to($user->email)
                //     ->bcc(Admin::find(28)->email)
                //     ->send(new SendMassageToUser( $sePrice , $user ,$date));

                    $admin=Admin::find(28);
                    config(['mail.username' => $admin->email]);
                    config(['mail.password'=>$admin->email_password]);
                    //dd( $admin->email, $admin->email_password);

                    Mail::to($user->email)
                    ->bcc('ailbaza156@gmail.com')
                    //->bcc(Admin::find(28)->email)
                    ->send(new SendMassageToUser( $sePrice , $user ,$date));

                return redirect()->route('admin.order.index')->with('flash_success',trans('admin.editMessageSuccessCars'));
            }
            // Mail::to($user->email)
            //     ->bcc(Admin::find(28)->email)
            //     ->send(new SendMassageToUserNotActive( $sePrice , $user ));

                $admin=Admin::find(28);
                config(['mail.username' => $admin->email]);
                config(['mail.password'=>$admin->email_password]);
                //dd( $admin->email, $admin->email_password);

                Mail::to($user->email)
                ->bcc('ailbaza156@gmail.com')
                //->bcc(Admin::find(28)->email)
                ->send(new SendMassageToUserNotActive( $sePrice , $user ));

            return redirect()->route('admin.order.index')->with('flash_error',trans('admin.wallet_balanceunCars'));
        }catch (\Exception $e){
            return $e;
            return redirect()->back()->with('flash_error',trans('admin.Something Went Wrong'));
        }


    }

    public function active ($id){
        try{

            $record = Request_car::whereId($id)->get()->first();
            if($record->status == 0){
                $to30=Carbon::now()->addDays(30);
                $to=date('Y-m-d',strtotime($to30));
                $sePrice = Settings::where('key','pay_for_ad')->get()->first()->value;
                $user=$record->user;
                if($record->to <= date('Y-m-d')){
                    if($record->user->wallet_balance >= $sePrice){
                        $record->update([
                            'status'=>1,
                            'from'=>date('d-m-Y'),
                            'to'=>$to
                        ]);
                        $date = date('d-m-Y',strtotime($to)-1);

                        $user->update([
                            'wallet_balance'=> $user->wallet_balance - $sePrice,
                        ]);
                        // Mail::to($user->email)
                        //     ->bcc(Admin::find(28)->email)
                        //     ->send(new SendMassageToUser( $sePrice , $user,$date));

                        $admin=Admin::find(28);
                        config(['mail.username' => $admin->email]);
                        config(['mail.password'=>$admin->email_password]);
                        //dd( $admin->email, $admin->email_password);

                        Mail::to($user->email)
                        ->bcc('ailbaza156@gmail.com')
                        //->bcc(Admin::find(28)->email)
                        ->send(new SendMassageToUser( $sePrice , $user,$date));

                        return redirect()->back()->with('flash_success',trans('admin.editMessageSuccess'));

                    }else{
                        // Mail::to($user->email)
                        //     ->bcc(Admin::find(28)->email)
                        //     ->send(new SendMassageToUserNotActive( $sePrice ,$user ));

                        $admin=Admin::find(28);
                        config(['mail.username' => $admin->email]);
                        config(['mail.password'=>$admin->email_password]);
                        //dd( $admin->email, $admin->email_password);

                        Mail::to($user->email)
                        ->bcc('ailbaza156@gmail.com')
                        //->bcc(Admin::find(28)->email)
                        ->send(new SendMassageToUserNotActive( $sePrice ,$user ));

                        return redirect()->back()->with('flash_error',trans('admin.wallet_balanceun'));
                    }

                }else{
                    $record->status=1;
                    $record->save();
                    return redirect()->back()->with('flash_success',trans('admin.editMessageSuccess'));
                }


            }else{
                return redirect()->back()->with('flash_error',trans('admin.this active'));
            }

        }catch (\Exception $e){
            //return $e;
            return redirect()->back()->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }
    public function unActive ($id){
        try{

            $record = Request_car::whereId($id)->get()->first();
            if($record->status == 1){

                    $record->update([
                        'status'=>0,

                    ]);
                return redirect()->back()->with('flash_success',trans('admin.editMessageSuccess'));
            }

        }catch (\Exception $e){
            //return $e;
            return redirect()->back()->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }
    public function edit($id){
        $cars_models= CarModel::select()->get();
        $car=Request_car::findOrFail($id);
        $governorates=Governorate::select()->get();
        return view('admin.requestCars.edit',compact('car','cars_models','governorates'));
    }
    public function update(Request $request,$id){
        try{

            $car=Request_car::whereId($id)->get()->first();
            if($request->hasFile('photo')) {
                //$photo_file1= Helper::upload_picture($request->file('photo'));

                $picture = $request->photo;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."p1";
                $picture->move('uploads/car/', $avatar_new_name);
                $x='uploads/car/' . $avatar_new_name;

                $car->photo1 = $x;
            }
            if($request->hasFile('photo2')) {
                //$photo_file2 = Helper::upload_picture($request->file('photo2'));

                $picture = $request->photo2;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."p2";
                $picture->move('uploads/car/', $avatar_new_name);
                $x='uploads/car/' . $avatar_new_name;

                $car->photo2 = $x;

            }
            if($request->hasFile('photo3')) {
                //$photo_file3 = Helper::upload_picture($request->file('photo3'));

                $picture = $request->photo3;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."p3";
                $picture->move('uploads/car/', $avatar_new_name);
                $x='uploads/car/' . $avatar_new_name;

                $car->photo3 = $x;

            }
            if($request->hasFile('photo4')) {
                //$photo_file4 = Helper::upload_picture($request->file('photo4'));

                $picture = $request->photo4;
                $code = rand(111111111, 999999999);
                $avatar_new_name=time().$code ."p4";
                $picture->move('uploads/car/', $avatar_new_name);
                $x='uploads/car/' . $avatar_new_name;
                $car->photo4 = $x;
            }
            //$car->update([

            //]);
            $car->save();
            $car->update([
                'id_models'=>$request->id_models,
                'id_governorate'=>$request->id_governorate,
                'number_seats'=>$request->number_seats,
                'full_type'=>$request->full_type,
                'gearbox'=>$request->gearbox,
                'color'=>$request->color,
                'note_ar'=>$request->note_ar,
                'note_en'=>$request->note_en,
                'price'=>$request->price,
                'lat'=> $request->latitude,
                'long'=> $request->longitude
              ]);

            return redirect()->route('admin.order.index')->with('flash_success',trans('admin.editMessageSuccess'));
        }catch (\Exception $e){
            return $e;
            return redirect()->back()->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }
    public function destroy($id)
    {
        try{

            $record = Request_car::whereId($id)->get()->first();

            if ($record->status == 0){
                if($record->photo1 != null ) {
                    if (File::exists(public_path().$record->photo1)) {
                        //File::delete($image_path);
                        unlink(public_path().$record->photo1);
                    }
                }
                if($record->photo2 != null ) {
                    if (File::exists(public_path().$record->photo2)) {
                        //File::delete($image_path);
                        unlink(public_path().$record->photo2);
                    }
                }
                if($record->photo3 != null ) {
                    if (File::exists(public_path().$record->photo3)) {
                        //File::delete($image_path);
                        unlink(public_path().$record->photo3);
                    }
                }
                if($record->photo4 != null ) {
                    if (File::exists(public_path().$record->photo4)) {
                        //File::delete($image_path);
                        unlink(public_path().$record->photo4);
                    }

                }

                $record->delete();
                return redirect()->back()->with('flash_success',trans('admin.deleteMessageSuccess'));
                //flash()->success(trans('admin.deleteMessageSuccess'));
                //return back();
            }else{
                return redirect()->back()->with('flash_error',trans('admin.notDeleteCar'));
            }

        }catch (\Exception $e){
            //return $e;
            return redirect()->back()->with('flash_error',trans('admin.Something Went Wrong'));
        }


    }
}
