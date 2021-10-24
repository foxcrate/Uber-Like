<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use App\Revenue;
use App\Admin;
use App\M_revenue;

use App\Provider;
use App\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// use DateTime;
// use DatePeriod;
// use DateInterval;
use App\Mail\SendmassageToprovider;
use Illuminate\Support\Facades\Mail;
class RevenueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $Provider=Provider::select()->get();
      //  $Provider=Provider::select()->get();
        // return $Provider->serviceTypes;
        $records = Revenue::select()->get();
        //return $records;
        // $service_types=$Provider->serviceTypes;

        //dd($Provider,$records);

        return view('admin.revenues.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        //dd("Aloo");
        $revenuesMonthly = M_revenue::where('history','=', date('Y-m'))->get()->count();
        $month = date('m');
        //return $revenuesMonthly;
        $providers = Provider::where('deleted_at', null)->get();
        $service_types=ServiceType::where('status','1')->get();
        // $newDate=date('Y-m-d') ;
        // $newDate = new DateTime();
        // $newDate->add(new DateInterval('P10D'));
        // // $newDate->format('Y-m-d');
        // $newDate=date("Y-m-d", timetostr($newDate));
        $newDate2 = Carbon::today();
        $newDate2->addDay(20);
        $newDate=$newDate2->toDateString();

        $d202=Carbon::createFromDate(null, null, 20);
        $d20=$d202->toDateString();

        $d3=$formatted_dt1=Carbon::parse(1);

        return view('admin.revenues.create', compact('d3','d20','newDate','providers','service_types','revenuesMonthly','month'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'provider_id' => 'required|exists:providers,id',
           // 'money' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);

        //dd($request);
        if(isset($request->gift)){
            //dd("True");
            try{
                //return $request;
                // dd($request->from < $request->to);
                $Provider=Provider::where('id',$request->provider_id)->get()->first();
                //dd($Provider->service->service_type);
                //dd($Provider->service->service_type->sub_com);
                //$monyForDay =$Provider->serviceTypes[0]->sub_com/30;
                $monyForDay = $Provider->service->service_type->sub_com;
                $status='active';
                if(date("d")< 20 && date("d")!= 19){
                    $ggg = date("Y-m-d");
                    $ggg1 = date("Y-m-20");
                    $formatted_dt1=Carbon::parse($ggg1);
                    $formatted_dt2=Carbon::parse($ggg);
                    $date_diff=$formatted_dt2->diffInDays($formatted_dt1);
                    $price=$monyForDay*$date_diff;

                    $record = Revenue::create([
                        'provider_id' => $request->get('provider_id'),
                        'money' => $price,
                        'from' =>$formatted_dt2,
                        'to' => $formatted_dt1,
                        'status' => $status
                    ]);
                    $record->gift=1;
                    $record->save();

                    // return $price."الحالة".$status."من والي". $formatted_dt2. $formatted_dt1;
                }elseif(date("d") == 20 ){
                    $ggg = date("Y-m-d");
                    $formatted_dt1=Carbon::parse($request->to);
    
                    $formatted_dt2=Carbon::parse($ggg);
    
                    $date_diff=$formatted_dt2->diffInDays($formatted_dt1);
                    $price=$monyForDay*$date_diff;

                    $record = Revenue::create([
                        'provider_id' => $request->get('provider_id'),
                        'money' => $price,
                        'from' =>$formatted_dt2,
                        'to' => $formatted_dt1,
                        'status' => $status
                    ]);
                    $record->gift=1;
                    $record->save();

                    // return $date_diff."////عشرين === بتساوي \n".$monyForDay."===".$price."\nالحالة".$status."\nمن والي". $formatted_dt2. $formatted_dt1;
                }elseif(date("d") == 19 ){
                    $ggg = date("Y-m-19");
                    $ggg1 = date("Y-m-20");
    
                    $formatted_dt1=Carbon::parse($ggg1);
    
                    $formatted_dt2=Carbon::parse($ggg);
    
                    $date_diff=$formatted_dt2->diffInDays($formatted_dt1);
                    $price=0;
                    $status='not_active';

                    $record = Revenue::create([
                        'provider_id' => $request->get('provider_id'),
                        'money' => $price,
                        'from' =>$formatted_dt2,
                        'to' => $formatted_dt1,
                        'status' => $status
                    ]);
                    $record->gift=1;
                    $record->save();

                    // return $price."الحالة".$status."من والي". $formatted_dt2. $formatted_dt1;
                }elseif(date("d") > 20 ){
                    $ggg = date("Y-m-d");
                    $formatted_dt1=Carbon::parse($request->to);
    
                    $formatted_dt2=Carbon::parse($ggg);
    
                    $date_diff=$formatted_dt2->diffInDays($formatted_dt1);
    
                    $price=$monyForDay*$date_diff;

                    $record = Revenue::create([
                        'provider_id' => $request->get('provider_id'),
                        'money' => $price,
                        'from' =>$formatted_dt2,
                        'to' => $formatted_dt1,
                        'status' => $status
                    ]);
                    $record->gift=1;
                    $record->save();

                    // dd( $date_diff."////اكبر".$monyForDay."===".$price."\nالحالة".$status."من والي\n". $formatted_dt2."\n". $formatted_dt1);
                }else{
                    flash()->error(trans('admin.Something Went Wrong'));
                    return redirect()->back();
                }
    
                flash()->success(trans('admin.createMessageSuccess'));
                return redirect(route('admin.revenue.index'));
    
            }catch(\Exception $e){
                // dd($request->to);
                //dd($e);
                flash()->error(trans('admin.Something Went Wrong'));
                return redirect()->back();
            }

        }
        else{
            //dd("False");
            try{
                $this->validate($request, [
                    'provider_id' => 'required|exists:providers,id',
                   // 'money' => 'required',
                    'from' => 'required',
                    'to' => 'required',
                ]);
                //return $request;
                // dd($request->from < $request->to);
                $Provider=Provider::where('id',$request->provider_id)->get()->first();
                //dd($Provider->service->service_type);
                //dd($Provider->service->service_type->sub_com);
                //$monyForDay =$Provider->serviceTypes[0]->sub_com/30;
                $monyForDay = $Provider->service->service_type->sub_com;
                $status='active';
                if(date("d")< 20 && date("d")!= 19){
                    $ggg = date("Y-m-d");
                    $ggg1 = date("Y-m-20");
                    $formatted_dt1=Carbon::parse($ggg1);
                    $formatted_dt2=Carbon::parse($ggg);
                    $date_diff=$formatted_dt2->diffInDays($formatted_dt1);
                    $price=$monyForDay*$date_diff;
                    // return $price."الحالة".$status."من والي". $formatted_dt2. $formatted_dt1;
                }elseif(date("d") == 20 ){
                    flash()->error(trans('admin.Something Went Wrong'));
                    return redirect()->back();
                    $ggg = date("Y-m-d");
                    $formatted_dt1=Carbon::parse($request->to);
    
                    $formatted_dt2=Carbon::parse($ggg);
    
                    $date_diff=$formatted_dt2->diffInDays($formatted_dt1);
                    $price=$monyForDay*$date_diff;
                    // return $date_diff."////عشرين === بتساوي \n".$monyForDay."===".$price."\nالحالة".$status."\nمن والي". $formatted_dt2. $formatted_dt1;
                }elseif(date("d") == 19 ){
                    flash()->error(trans('admin.Something Went Wrong'));
                    return redirect()->back();
                    $ggg = date("Y-m-19");
                    $ggg1 = date("Y-m-20");
    
                    $formatted_dt1=Carbon::parse($ggg1);
    
                    $formatted_dt2=Carbon::parse($ggg);
    
                    $date_diff=$formatted_dt2->diffInDays($formatted_dt1);
                    $price=0;
                    $status='not_active';
                    // return $price."الحالة".$status."من والي". $formatted_dt2. $formatted_dt1;
                }elseif(date("d") > 20 ){
                    $ggg = date("Y-m-d");
                    $formatted_dt1=Carbon::parse($request->to);
    
                    $formatted_dt2=Carbon::parse($ggg);
    
                    $date_diff=$formatted_dt2->diffInDays($formatted_dt1);
    
                    $price=$monyForDay*$date_diff;
                    // dd( $date_diff."////اكبر".$monyForDay."===".$price."\nالحالة".$status."من والي\n". $formatted_dt2."\n". $formatted_dt1);
                }else{
                    flash()->error(trans('admin.Something Went Wrong'));
                    return redirect()->back();
                }
    
                 $provider = Provider::where('id',$request->get('provider_id'))->get()->first();
                $wallet_balance =$provider->wallet_balance;
    
               if($wallet_balance >= $price){
                    $new_wallet_balance=$wallet_balance - $price;
                    $provider->update([
                        'wallet_balance'=>$new_wallet_balance
                    ]);
                    $record = Revenue::create([
                        'provider_id' => $request->get('provider_id'),
                        'money' => $price,
                        'from' =>$formatted_dt2,
                        'to' => $formatted_dt1,
                        'status' => $status
                    ]);
                    $date = date('d-m-Y',strtotime($formatted_dt1)-1);
                    if($status != 'not_active' or $price > 0 ){
                        // Mail::to($provider->email)
                        // ->bcc(Admin::find(28)->email)
                        // ->send(new SendmassageToprovider( $price, $provider,$date));
    
                        $admin=Admin::find(28);
                        config(['mail.username' => $admin->email]);
                        config(['mail.password'=>$admin->email_password]);
                        //dd( $admin->email, $admin->email_password);
    
                        //dd($provider->profile());
    
                        Mail::to($provider->email)
                        ->bcc('ailbaza156@gmail.com')
                        //->bcc(Admin::find(28)->email)
                        ->send(new SendmassageToprovider( $price, $provider,$date));
    
                    }
                    flash()->success(trans('admin.createMessageSuccess'));
                    return redirect(route('admin.revenue.index'));
    
               }else{
                    flash()->error(trans('admin.wallet_balanceun'));
                    return redirect()->back();
               }
    
            }catch(\Exception $e){
                // dd($request->to);
                //dd($e);
                flash()->error(trans('admin.Something Went Wrong'));
                return redirect()->back();
            }

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
    // public function edit($id)
    // {
    //     $model = Revenue::findOrFail($id);
    //     $provider = Provider::where('deleted_at', null)->get();
    //     $service_types=ServiceType::where('status','1')->get();

    //     return view('admin.revenues.edit', compact('model','provider','service_types'));
    // }
    public function edit($id)
    {
        try{
        $reven =Revenue::where('id',$id)->get()->first();

        $Provider=Provider::where('id',$reven->provider_id)->get()->first();

        $monyForDay =$Provider->serviceTypes[0]->sub_com/30;
      $status='active';
      if(date("d")< 20 && date("d")!= 19){
          $ggg = date("Y-m-d");
          $ggg1 = date("Y-m-20");
          $formatted_dt1=Carbon::parse($ggg1);
          $formatted_dt2=Carbon::parse($ggg);
          $date_diff=$formatted_dt2->diffInDays($formatted_dt1);
          $price=$monyForDay*$date_diff;
          // return $price."الحالة".$status."من والي". $formatted_dt2. $formatted_dt1;
      }elseif(date("d") == 20 ){
          $ggg = date("Y-m-d");
          $formatted_dt1=Carbon::parse($request->to);

          $formatted_dt2=Carbon::parse($ggg);

          $date_diff=$formatted_dt2->diffInDays($formatted_dt1);
          $price=$monyForDay*$date_diff;
          // return $date_diff."////عشرين === بتساوي \n".$monyForDay."===".$price."\nالحالة".$status."\nمن والي". $formatted_dt2. $formatted_dt1;
      }elseif(date("d") == 19 ){
          $ggg = date("Y-m-19");
          $ggg1 = date("Y-m-20");

          $formatted_dt1=Carbon::parse($ggg1);

          $formatted_dt2=Carbon::parse($ggg);

          $date_diff=$formatted_dt2->diffInDays($formatted_dt1);
          $price=0;
          $status='not_active';
          // return $price."الحالة".$status."من والي". $formatted_dt2. $formatted_dt1;
      }elseif(date("d") > 20 ){
          $ggg = date("Y-m-d");
          $formatted_dt1=Carbon::parse($request->to);

          $formatted_dt2=Carbon::parse($ggg);

          $date_diff=$formatted_dt2->diffInDays($formatted_dt1);

          $price=$monyForDay*$date_diff;
          // dd( $date_diff."////اكبر".$monyForDay."===".$price."\nالحالة".$status."من والي\n". $formatted_dt2."\n". $formatted_dt1);
      }else{
          flash()->error(trans('admin.Something Went Wrong'));
          return redirect()->back();
      }

       $provider = Provider::where('id',$reven->provider_id)->get()->first();
      $wallet_balance =$provider->wallet_balance;

     if($wallet_balance >= $price){
      $new_wallet_balance=$wallet_balance - $price;
      $provider->update([
          'wallet_balance'=>$new_wallet_balance
      ]);
      $reven->update([
          'money' => $price,
          'from' =>$formatted_dt2,
          'to' => $formatted_dt1,
          'status' => $status
      ]);
       $date = date('d-m-Y',strtotime($formatted_dt1)-1);
      if($status != 'not_active' or $price > 0 ){
        //   Mail::to($provider->email)
        //   ->bcc(Admin::find(28)->email)
        //   ->send(new SendmassageToprovider( $price, $provider,$date));

          $admin=Admin::find(28);
          config(['mail.username' => $admin->email]);
          config(['mail.password'=>$admin->email_password]);
          //dd( $admin->email, $admin->email_password);

          Mail::to($provider->email)
          ->bcc('ailbaza156@gmail.com')
          //->bcc(Admin::find(28)->email)
          ->send(new SendmassageToprovider( $price, $provider,$date));

      }
      flash()->success(trans('admin.createMessageSuccess'));
      return redirect(route('admin.revenue.index'));

     }else{
      flash()->error(trans('admin.wallet_balanceun'));
          return redirect()->back();
     }




   }catch(Exception $e){

      flash()->error(trans('admin.Something Went Wrong'));
          return $e;
  }
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
        $records = Revenue::findOrFail($id);
            $this->validate($request, [
                'provider_id' => 'required|exists:providers,id',
                'money' => 'required',
                'from' => 'required',
                'to' => 'required',
            ]);

        if ($request->from < $request->to && date("Y-m-d") >= $request->from) {

            $records->update([
                'provider_id' => $request->get('provider_id'),
                'money' => $request->get('money'),
                'from' => $request->get('from'),
                'to' => $request->get('to'),
                'status' => 'active'
            ]);
            flash()->success(trans('admin.editMessageSuccess'));
            return redirect(route('admin.revenue.index'));
        } elseif ($request->from < $request->to && date("Y-m-d") != $request->from) {
            $records->update([
                'provider_id' => $request->get('provider_id'),
                'money' => $request->get('money'),
                'from' => $request->get('from'),
                'to' => $request->get('to'),
                'status' => 'not_active'
            ]);
            flash()->success(trans('admin.editMessageSuccess'));
            return redirect(route('admin.revenue.index'));
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
        $record = Revenue::findOrFail($id);
        $record->delete();
        flash()->success(trans('admin.deleteMessageSuccess'));
        return back();
    }
}
