<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\M_revenue;
use App\Revenue;
use App\UserRequests;
use App\UserRequestPayment;
use Carbon\Carbon;
class CreatedMRes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CreatedMRes';

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
        $Revenue=Revenue::where("status","active")->sum('money');
        //echo $Revenue;
        $Revenue_rits=UserRequestPayment::select()->get();
        $allSum =0;
        foreach($Revenue_rits as $Revenue_rit){
            $value=Carbon::parse($Revenue_rit->created_at)->format('Y-m');
            if($value == date('Y-m')){
                //echo $value. "==".date('Y-m')."//////////////";
               $allSum = $allSum + $Revenue_rit->commision;
            }
            // elseif($value !== date('Y-m')){
            //    echo "1-------------------------------------";
            //    $allSum = $allSum - $Revenue_rit->commision;
            // }
           
        //echo date('M-Y',$Revenue_rit->created_at).'////////////';
        }
        // echo $allSum ;
    //    echo $Revenue_rit;
         M_revenue::create([
            'revenues_sub'=>$Revenue,
            "revenues_ri"=>$allSum,
            "history"=>date('Y-m')

            ]);
       // echo "eeeee";
    }
}
