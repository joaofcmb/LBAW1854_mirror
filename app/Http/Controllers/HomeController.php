<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Team;
use App\Project;
use App\Developer;

class HomeController extends Controller
{

    public function show() {
        if (!Auth::check()) return redirect('/login');

        $management = Developer::find()->manager;

        foreach ($management as $var)
        {
            echo "--- " . $var . "<br>";
        }

        die();

        return view('pages.home', ['management' => $management]);
    }

}
