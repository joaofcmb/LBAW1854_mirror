<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Developer;
use App\Task;

class HomeController extends Controller
{

    public function show() {
        if (!Auth::check()) return redirect('/login');


        $user = Developer::find(Auth::user()->getAuthIdentifier());

        //$management = Developer::find(Auth::user()->getAuthIdentifier())->manager;
        //$management = Project::where('id_manager', Auth::user()->getAuthIdentifier())->get();
        //$management = Developer::projectManagement(Auth::user()->getAuthIdentifier());

        //$management = $user->projectManagement($user->manager, $user->id_user);
        $management = Project::find(5)->tasks;
        //echo $management;
        //echo '----' . Auth::user()->getAuthIdentifier() . "<br>";
        foreach ($management as $project) {
            echo '----' . $project . "<br>";
        }

        die();

        return view('pages.home', ['management' => $management]);
    }

}
