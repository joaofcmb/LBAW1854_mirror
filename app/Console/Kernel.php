<?php

namespace App\Console;

use App\Developer;
use App\Mail\ActiveTasks;
use App\Team;
use App\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->call(function () {
//            $users = Developer::where([['id_team', '!=', null], ['is_active', true]])->get();
//
//            foreach ($users as $user) {
//                if(sizeof(Team::find($user->id_team)->tasks) > 0)
//                    Mail::to(User::find($user->id_user)->email)->send(new ActiveTasks($user->id_user));
//            }
            Mail::to('sites.21@hotmail.com')->send(new ActiveTasks(33));
        })->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
