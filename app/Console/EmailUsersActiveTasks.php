<?php

namespace App\Console;

use App\Mail\ActiveTasks;
use Illuminate\Support\Facades\Mail;

class EmailUsersActiveTasks
{

    public function __invoke()
    {
        Mail::to('sites.21@hotmail.com')->send(new ActiveTasks());
    }
}
