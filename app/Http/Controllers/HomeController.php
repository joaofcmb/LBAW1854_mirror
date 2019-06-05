<?php

namespace App\Http\Controllers;

use App\Mail\ActiveTasks;
use App\Mail\ActiveTasksss;
use App\Thread;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Project;
use App\Developer;
use App\Team;
use App\Task;
use App\Forum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show() {
        if (!Auth::check()) return redirect('/login');

        $threads = Forum::find(1)->threads->take(7);

        if(Auth::user()->getAuthIdentifier() == 1 || Auth::user()->getAuthIdentifier() == 2)
            return view('pages.home', ['managementProjects' => [], 'teamProjects' => [], 'teamTasks' => [], 'threads' => $threads]);

        $user = Developer::find(Auth::user()->getAuthIdentifier());
        $team = Team::find($user->id_team);

        if (isset($team)) {
            return view('pages.home', ['managementProjects' => Project::information($user->manager),
                'teamProjects' => Project::information($team->projects),
                'teamTasks' => Task::information($team->tasks),
                'threads' => $threads]);
        }
        else {
            return view('pages.home', ['managementProjects' => Project::information($user->manager),
                'teamProjects' => [],
                'teamTasks' => [],
                'threads' => $threads]);
        }
    }

    public function activeTasks() {

        //echo User::find(33);
        $user = Developer::find(33);
        $team = Team::find($user->id_team);

        Mail::to('sites.21@hotmail.com')->send(new ActiveTasks(33));

        echo User::find(33);
        echo  Task::information($team->tasks);
        //return view('emails.activeTasks', ['teamTasks' => Task::information($team->tasks)]);

    }

}
