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

        $threads = Thread::threadInformation(Forum::find(1)->threads->take(7));

        if(Auth::user()->getAuthIdentifier() == 1 || Auth::user()->getAuthIdentifier() == 2)
            return view('pages.home', ['managementProjects' => [], 'teamProjects' => [], 'teamTasks' => [], 'threads' => $threads]);

        $user = Developer::find(Auth::user()->getAuthIdentifier());
        $team = Team::find($user->id_team);

        $managementProjects = Project::cardInformation($user->manager, $user->id_user);
        $teamProjects = Project::cardInformation($team->projects, $user->id_user);
        $teamTasks = Task::cardInformation($team->tasks);

        return view('pages.home', ['managementProjects' => $managementProjects, 'teamProjects' => $teamProjects, 'teamTasks' => $teamTasks, 'threads' => $threads]);
    }

}
