<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

use function Laravel\Prompts\error;

class ExpiredActivateCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expired-activate-code';

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
        $users = User::where('otp', '<>', null)->select('reset_otp','reset_otp_date')->get();
        foreach ($users as $user) {
            $otp_expires = Carbon::now('Asia/Riyadh');
            $otp = Carbon::parse($user->otp_date)->addMinute(2);
            $totalDuration = $otp_expires->diffInMinutes($otp);

            if($totalDuration == 0){
                $user->update([
                    'otp' =>null
                ]);
            }

        }
    }
}
