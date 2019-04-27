<?php

namespace App\Http\Controllers;

use App\Developer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdministratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource for users
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showUsers()
    {
        if(!Auth::user()->isAdmin())
            return redirect()->route('404');

        $users = Developer::join('user', 'user.id', '=', 'developer.id_user')->get();

        return View('pages.admin.adminUsers', ['users' => $users]);

    }

    /**
     * Display the specified resource for teams
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showTeams()
    {
        if(!Auth::user()->isAdmin())
            return redirect()->route('404');
    }

    /**
     * Display the specified resource for projects
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showProjects()
    {
        if(!Auth::user()->isAdmin())
            return redirect()->route('404');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
