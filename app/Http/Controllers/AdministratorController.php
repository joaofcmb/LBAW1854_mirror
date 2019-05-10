<?php

namespace App\Http\Controllers;

use App\Developer;
use App\Follow;
use App\Project;
use App\Team;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Translation\Dumper\PoFileDumper;

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
     * Show the form for creating a new resource: team
     *
     * @return \Illuminate\Http\Response
     */
    public function createTeam()
    {
        if(!Auth::user()->isAdmin())
            return redirect()->route('404');

        $users = Developer::select('user.id', 'user.username')
                            ->join('user', 'user.id', '=', 'developer.id_user')
                            ->where([['developer.is_active', 'true'], ['developer.id_team', null]])
                            ->get();

        return View('pages.admin.adminManageTeam', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource: project
     *
     * @return \Illuminate\Http\Response
     */
    public function createProject()
    {
        if(!Auth::user()->isAdmin())
            return redirect()->route('404');

        return View('pages.admin.adminManageProject');
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

        $users = Developer::select('user.id', 'user.username', 'developer.is_active')
            ->join('user', 'user.id', '=', 'developer.id_user')
            ->get();

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

        return View('pages.admin.adminTeams', ['teams' => Team::all()]);
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

        return View('pages.admin.adminProjects', ['projects' =>  Project::information(Project::all())]);
    }

    /**
     * Show the form for editing the specified resource: team
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editTeam($id)
    {
        $team = Team::find($id);

        if(!Auth::user()->isAdmin() || empty($team))
            return redirect()->route('404');

        $team = Team::information($team);

        $membersIds = [];
        foreach ($team['members'] as $member) {
            array_push($membersIds, $member->id);
        }

        $users = Developer::select('user.id', 'user.username', 'developer.is_active')
                            ->join('user', 'user.id', '=', 'developer.id_user')
                            ->where('developer.is_active', 'true')
                            ->whereNotIn('user.id', $membersIds)
                            ->get();

        return View('pages.admin.adminManageTeam', ['users' => $users, 'team' => $team]);
    }

    /**
     * Show the form for editing the specified resource: project
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editProject($id)
    {
        $project = Project::find($id);

        if(!Auth::user()->isAdmin() || empty($project))
            return redirect()->route('404');

        $project['manager'] = User::where('id', $project->id_manager)->value('username');

        return View('pages.admin.adminManageProject', ['project' => $project]);
    }

    /**
     * 
     */
    public function removeUser($id)
    {
        $user = Developer::find($id);
        
        if(Project::where('id_manager', $id)->exists() || Team::where('id_leader', $id)->exists())
            return response("", 400, []);
        
        $user->is_active = FALSE;
        $user->save();
    }

    /**
     * 
     */
    public function restoreUser($id)
    {
        $user = Developer::find($id);
        $user->is_active = TRUE;
        $user->save();        
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
