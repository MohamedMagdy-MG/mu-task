<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpiredResetCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expired-reset-code';

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
        $users = User::where('reset_otp', '<>', null)->select('reset_otp','reset_otp_date')->get();
        foreach ($users as $user) {
            $reset_otp_expires = Carbon::now('Asia/Riyadh');
            $reset_otp = Carbon::parse($user->otp_date)->addMinute(2);
            $totalDuration = $reset_otp_expires->diffInMinutes($reset_otp);

            if($totalDuration == 0){
                $user->update([
                    'reset_otp' =>null
                ]);
            }

        }
    }
}
