<?php

namespace App\Http\Controllers\User;

use App\CarModel;
use App\Admin;
use App\Governorate;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CarsRequest;

use App\Mail\SendMassageToUser;
use App\Mail\SendMassageToUserNotActive;
use App\Request_car;
use App\Settings;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Mail;
use function PHPSTORM_META\elementType;
use Illuminate\Support\Facades\File;
//use File;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function MyCars_index()
    {
        $cars= Request_car::where('id_borr', Auth::user()->id)->get();
        return view('user.order_cars.myCars',compact("cars"));
    }

    public function get_creat()
    {
        $cars_models= CarModel::select()->get();
        $governorates= Governorate::select()->get();
        return view('user.order_cars.create',compact("cars_models","governorates"));
    }
    public function store(CarsRequest $request)
    {
        try{
            $photo_file1=null;
            $photo_file2=null;
            $photo_file3=null;
            $photo_file4=null;
            if ($request->latitude == '' or $request->latitude == null or $request->longitude == '' or $request->longitude == null){
                return redirect()->back()->with('flash_error',trans('admin.Something Went Wrong'));
            }
            if($request->hasFile('photo')) {
                //$photo_file1= Helper::upload_picture($request->file('photo'));

                $car_back = $request->file('photo');
                $code = rand(111111111, 999999999);
                $car_back_new_name = time() . $code ."p1";
                $car_back->move('uploads/car', $car_back_new_name);

                $photo_file1 = 'uploads/car/' . $car_back_new_name;
            }
            if($request->hasFile('photo2')) {
                //$photo_file2 = Helper::upload_picture($request->file('photo2'));

                $car_back = $request->file('photo2');
                $code = rand(111111111, 999999999);
                $car_back_new_name = time() . $code ."p2";
                $car_back->move('uploads/car', $car_back_new_name);

                $photo_file2 = 'uploads/car/' . $car_back_new_name;

            }
            if($request->hasFile('photo3')) {
                //$photo_file3 = Helper::upload_picture($request->file('photo3'));

                $car_back = $request->file('photo3');
                $code = rand(111111111, 999999999);
                $car_back_new_name = time() . $code ."p3";
                $car_back->move('uploads/car', $car_back_new_name);

                $photo_file3 = 'uploads/car/' . $car_back_new_name;
            }
            if($request->hasFile('photo4')) {
                //$photo_file4 = Helper::upload_picture($request->file('photo4'));

                $car_back = $request->file('photo4');
                $code = rand(111111111, 999999999);
                $car_back_new_name = time() . $code ."p4";
                $car_back->move('uploads/car', $car_back_new_name);

                $photo_file4 = 'uploads/car/' . $car_back_new_name;
            }
            $year= CarModel::whereId($request->id_models)->first()->date;
            $sePrice = Settings::where('key','pay_for_ad')->get()->first()->value;

            $to30=Carbon::now()->addDays(30);
            $to=date('Y-m-d',strtotime($to30));
            $record= Request_car::create([
                'id_borr'=>Auth::user()->id,
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
            if(Auth::user()->wallet_balance >= $sePrice){

                $record->update([
                    'status'=>1,
                    'from'=>date('d-m-Y'),
                    'to'=>$to
                ]);
                $date = date('d-m-Y',strtotime($to)-1);
                Auth::user()->update([
                    'wallet_balance'=> Auth::user()->wallet_balance - $sePrice,
                ]);
                // Mail::to(Auth::user()->email)
                //     ->bcc(Admin::find(28)->email)
                //     ->send(new SendMassageToUser( $sePrice , Auth::user() ,$date));

                    $admin=Admin::find(28);
                    config(['mail.username' => $admin->email]);
                    config(['mail.password'=>$admin->email_password]);
                    //dd( $admin->email, $admin->email_password);

                    Mail::to(Auth::user()->email)
                    ->bcc('ailbaza156@gmail.com')
                    //->bcc(Admin::find(28)->email)
                    ->send(new SendMassageToUser( $sePrice , Auth::user() ,$date));


                return redirect()->route('order.MyCars.index')->with('flash_success',trans('admin.editMessageSuccessCars'));
            }
            // Mail::to(Auth::user()->email)
            //     ->bcc(Admin::find(28)->email)
            //     ->send(new SendMassageToUserNotActive( $sePrice , Auth::user() ));

                $admin=Admin::find(28);
                config(['mail.username' => $admin->email]);
                config(['mail.password'=>$admin->email_password]);
                //dd( $admin->email, $admin->email_password);

                Mail::to(Auth::user()->email)
                ->bcc('ailbaza156@gmail.com')
                //->bcc(Admin::find(28)->email)
                ->send(new SendMassageToUserNotActive( $sePrice , Auth::user() ));

            return redirect()->route('order.MyCars.index')->with('flash_error',trans('admin.wallet_balanceunCars'));
        }catch (\Exception $e){
            return $e;
            return redirect()->back()->with('flash_error',trans('admin.Something Went Wrong'));
        }

    }
    public function view($id){
        $car = Request_car::whereId($id)->get()->first();
        return view('user.order_cars.Car',compact("car"));
    }
    public function active ($id){
        try{

            $record = Request_car::whereId($id)->get()->first();
            if($record->status == 0){
                $to30=Carbon::now()->addDays(30);
                $to=date('Y-m-d',strtotime($to30));
                  $sePrice = Settings::where('key','pay_for_ad')->get()->first()->value;
                $user=$record->user;
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
                    //   Mail::to($user->email)
                    //       ->bcc(Admin::find(28)->email)
                    //       ->send(new SendMassageToUser( $sePrice , $user,$date));

                          $admin=Admin::find(28);
                          config(['mail.username' => $admin->email]);
                          config(['mail.password'=>$admin->email_password]);
                          //dd( $admin->email, $admin->email_password);

                          Mail::to($user->email)
                          ->bcc('ailbaza156@gmail.com')
                          //->bcc(Admin::find(28)->email)
                          ->send(new SendMassageToUser( $sePrice , $user,$date));

                  return redirect()->route('order.MyCars.index')->with('flash_success',trans('admin.editMessageSuccess'));

              }else{
                //   Mail::to($user->email)
                //       ->bcc(Admin::find(28)->email)
                //       ->send(new SendMassageToUserNotActive( $sePrice ,$user ));

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
                return redirect()->back()->with('flash_error',trans('admin.this active'));
            }

        }catch (\Exception $e){
//            return $e;
            return redirect()->back()->with('flash_error',trans('admin.Something Went Wrong'));
        }
    }
    public function destroy($id)
    {
        try{
            $record = Request_car::whereId($id)->get()->first();
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
            flash()->success(trans('admin.deleteMessageSuccess'));
            return back();
        }catch (\Exception $e){
//            return $e;
            return redirect()->back()->with('flash_error',trans('admin.Something Went Wrong'));
        }

    }
}
