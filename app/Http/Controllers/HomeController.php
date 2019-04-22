<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Developer;
use App\Team;
use App\Task;

class HomeController extends Controller
{

    public function show() {
        if (!Auth::check()) return redirect('/login');


        $user = Developer::find(Auth::user()->getAuthIdentifier());
        $team = Team::find($user->id_team);
        //$management = Developer::find(Auth::user()->getAuthIdentifier())->manager;
        //$management = Project::where('id_manager', Auth::user()->getAuthIdentifier())->get();
        //$management = Developer::projectManagement(Auth::user()->getAuthIdentifier());

        $managementProjects = Project::cardInformation($user->manager, $user->id_user);
        $teamProjects = Project::cardInformation($team->projects, $user->id_user);
        $teamTasks = Task::cardInformation($team->tasks, $user->id_user);

        //echo $teamTasks;
        //echo $teamProjects;
       // echo $managementProjects;
        //echo '----' . Auth::user()->getAuthIdentifier() . "<br>";
        foreach ($managementProjects as $project) {
            //echo '----' . $project . "<br>";
        }

        //die();

        return view('pages.home', ['managementProjects' => $managementProjects, 'teamProjects' => $teamProjects, 'teamTasks' => $teamTasks]);
    }

}
