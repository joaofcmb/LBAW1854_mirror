<?php

namespace App\Http\Controllers;

use App\Developer;
use App\Follow;
use App\Team;
use App\User;
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
    public function createTeam()
    {
        if(!Auth::user()->isAdmin())
            return redirect()->route('404');

        $users = Developer::select('user.id', 'user.username')
                            ->join('user', 'user.id', '=', 'developer.id_user')
                            ->where([['developer.is_active', 'true'],['developer.id_team', null]])
                            ->get();

        return View('pages.admin.adminCreateTeam', ['users' => $users]);
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

        $users = Developer::select('user.id', 'user.username', 'developer.is_active')->join('user', 'user.id', '=', 'developer.id_user')->get();

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


        $teams = Team::all();

        foreach ($teams as $team) {
            $team['leader'] = User::select('user.id', 'user.username')->where('id', $team->id_leader)->first();
            $team['members'] = Developer::select('user.id', 'user.username')->join('user', 'user.id', '=', 'developer.id_user')->where('developer.id_team', $team->id)->get();
        }

        return View('pages.admin.adminTeams', ['teams' => $teams]);
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
    public function editTeam($id)
    {

        $team = Team::find($id);

        if(!Auth::user()->isAdmin() || empty($team))
            return redirect()->route('404');

        $team['leader'] = User::select('user.id', 'user.username')->where('id', $team->id_leader)->first();
        $team['members'] = Team::teamInformation($team->members);

        $members = [];
        foreach ($team['members'] as $member) {
            array_push($members, $member->id_user);
        }

        $users = Developer::select('user.id', 'user.username', 'developer.is_active')
                            ->join('user', 'user.id', '=', 'developer.id_user')
                            ->where('developer.is_active', 'true')
                            ->whereNotIn('user.id', $members)
                            ->get();

        return View('pages.admin.adminEditTeam', ['users' => $users, 'team' => $team]);
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
