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
     * Creates a new project
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function createProjectAction()
    {
        if(!Auth::user()->isAdmin())
            return redirect()->route('404');

        $name = $_POST['name'];
        $color = $_POST['color'];
        $description = $_POST['description'];
        $id_manager = $_POST['projectManager'];

        $project = new Project;

        $project->name = $name;
        $project->color = $color;
        $project->description = $description;
        $project->id_manager = $id_manager;

        $project->save();

        return redirect()->route('admin-projects');
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
     * Edits a project
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function editProjectAction($id)
    {
        if(!Auth::user()->isAdmin())
            return redirect()->route('404');

        $name = $_POST['name'];
        $color = $_POST['color'];
        $description = $_POST['description'];
        $id_manager = $_POST['projectManager'];

        $project = Project::find($id);

        $project->name = $name;
        $project->color = $color;
        $project->description = $description;
        $project->id_manager = $id_manager;

        $project->save();

        return redirect()->route('admin-projects');
    }



    /**
     * 
     */
    public function removeUser($id)
    {
        try {
            $user = Developer::find($id);
            $user->is_active = false;
            $user->save();
        } catch (\Exception $exception) {
            return response('', 400, []);
        }
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
     * Remove a specific project
     *
     * @param $id_project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelProject($id_project) 
    {
        if(!Auth::user()->isAdmin())
            return redirect()->route('404');

        Project::destroy($id_project);

        return redirect()->route('admin-projects');
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
