<?php

namespace App\Console\Commands;

use App\Otp;
use Illuminate\Console\Command;

class otpExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:expireAll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete All Rows In OTP Table';

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
        Otp::truncate();
        return 0;
    }
}
