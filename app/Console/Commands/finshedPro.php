<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\M_revenue;
use App\Y_revenue;
use App\Revenue;
use App\Provider;
use App\Admin;
use App\UserRequests;
use App\UserRequestPayment;
use Carbon\Carbon;
use App\Mail\SendmassageToprovider;
use Illuminate\Support\Facades\Mail;
class finshedPro extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:finshedPro';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $revenues = Revenue::all();
        $month = date('m')+1;
        $dateNew= date('Y-'.$month.'-20');
        foreach ($revenues as $revenue) {
            $provider = Provider::where('id',$revenue->provider_id)->get()->first();
            $wallet_balance =$provider->wallet_balance;
            $monyOfday =  $provider->serviceTypes[0]->sub_com/30;
            $ggg = date("Y-m-d");
            $formatted_dt1=Carbon::parse( $dateNew);
            $formatted_dt2=Carbon::parse($ggg);
            $date_diff=$formatted_dt2->diffInDays($formatted_dt1);
            $price=$monyOfday*$date_diff;
            if($wallet_balance >= $price){

                $new_wallet_balance=$wallet_balance - $price;
                $provider->update([
                    'wallet_balance'=>$new_wallet_balance
                ]);
                $revenue->update([
                    'money' => $price,
                    'from' =>date("Y-m-d"),
                    'to' =>$dateNew,
                    'status'=>'active'
                ]);
                $date = date('d-m-Y',strtotime($formatted_dt1)-1);
                    // Mail::to($provider->email)
                    // ->bcc(Admin::find(28)->email)
                    // ->send(new SendmassageToprovider( $price, $provider,$date));

                    $admin=Admin::find(28);
                    config(['mail.username' => $admin->email]);
                    config(['mail.password'=>$admin->email_password]);
                    //dd( $admin->email, $admin->email_password);

                    Mail::to($provider->email)
                    ->bcc('ailbaza156@gmail.com')
                    //->bcc(Admin::find(28)->email)
                    ->send(new SendmassageToprovider( $price, $provider,$date));

            }else{
                $revenue->update([
                    'status'=>'time_finish',
                    'money'=>'0'
                ]);
            }
        }
    }
}
