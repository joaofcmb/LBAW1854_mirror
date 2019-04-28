<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Project;
use App\Developer;
use App\Team;
use App\Task;
use App\Forum;

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

        return view('pages.home', ['managementProjects' => Project::information($user->manager),
                                         'teamProjects' => Project::information($team->projects),
                                         'teamTasks' => Task::information($team->tasks),
                                         'threads' => $threads]);
    }

}
