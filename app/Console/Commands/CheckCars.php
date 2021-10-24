<?php

namespace App\Console\Commands;

use App\Request_car;
use Illuminate\Console\Command;

class CheckCars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:CheckCars';

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
     * @return int
     */
    public function handle()
    {
        $cars=Request_car::where('status', 1)->get();

        foreach ($cars as $car){
            if (date('Y-m-d',strtotime($car->to)) <= date('Y-m-d')){
                $car->update([
                    'status'=>0,
                ]);
            }

        }
    }
}
