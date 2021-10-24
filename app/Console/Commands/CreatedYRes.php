<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\M_revenue;
use App\Y_revenue;
use App\Revenue;
use App\UserRequests;
use App\UserRequestPayment;
use Carbon\Carbon;
class CreatedYRes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CreatedYRes';

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
        $Revenue_rits=M_revenue::select()->get();
        $allSumS =0;
        $allSumR =0;
        foreach($Revenue_rits as $Revenue_rit){
            $value=Carbon::parse($Revenue_rit->history)->format('Y');
            if($value == date('Y')){
                //echo $value. "==".date('Y-m')."//////////////";
               $allSumS = $allSumS + $Revenue_rit->revenues_sub;
               $allSumR = $allSumS + $Revenue_rit->revenues_ri;
            }
            // elseif($value !== date('Y-m')){
            //    echo "1-------------------------------------";
            //    $allSum = $allSum - $Revenue_rit->commision;
            // }
           
        //echo date('M-Y',$Revenue_rit->created_at).'////////////';
        }
        // echo $allSum ;
    //    echo $Revenue_rit;
         Y_revenue::create([
            'revenues_sub'=>$allSumS,
            "revenues_ri"=>$allSumR,
            "history"=>date('Y')

            ]);
       // echo "eeeee";
    }
}
