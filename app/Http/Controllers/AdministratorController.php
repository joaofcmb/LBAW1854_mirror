<?php

namespace App\Http\Controllers;

use App\Developer;
use App\Project;
use App\Team;
use App\User;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdministratorController extends Controller
{
    /**
     * Show the form for creating a new resource: team
     *
     * @return Response
     */
    public function createTeam()
    {
        if(!Auth::user()->isAdmin())
            return redirect()->route('404');

        $users = Developer::select('id', 'username', 'first_name', 'last_name')
                            ->join('user', 'user.id', '=', 'developer.id_user')
                            ->where([['developer.is_active', 'true'], ['developer.id_team', null]])
                            ->get();

        return View('pages.admin.adminManageTeam', ['users' => $users]);
    }

    /**
     * Create a new resource: team
     * 
     * @param Request $request
     * @return Response
     */
    public function createTeamAction(Request $request) 
    {
        if(!Auth::user()->isAdmin())
            return redirect()->route('404');

        $id_leader = $request->input('id_leader');

        if(Team::where('id_leader',intVal($id_leader))->exists()){
            $leader = User::find($id_leader);
            $message = "ERROR: ". $leader->first_name." ". $leader->last_name." is the leader of an existing team.";
            return redirect()->back()->withErrors($message);
        }
        
        $team = new Team();
        $team->name = $request->input('name');
        $team->skill = $request->input('skill');
        $team->id_leader = $id_leader;
        $team->save();

        $members = explode(',', $request->input('members'));

        if($members[0] == "")
            $members = [];

        array_push($members, $id_leader);

        foreach ($members as $id) {
            if(Team::where('id_leader',intVal($id))->whereNotIn('id',[$team->id])->exists()){
                $member = User::find($id);
                $message = "ERROR: ". $member->first_name." ". $member->last_name." is the leader of an existing team.";
                return redirect()->back()->withErrors($message);
            }

            $member = Developer::find($id);
            $member->id_team = $team->id;
            $member->save();
        }

        return redirect()->route('admin-teams');
    }

    /**
     * Show the form for creating a new resource: project
     *
     * @return Response
     */
    public function createProject()
    {
        if(!Auth::user()->isAdmin())
            return redirect()->route('404');

        return View('pages.admin.adminManageProject');
    }

    /**
     * Create a new resource: project
     *
     * @return Factory|RedirectResponse|View
     */
    public function createProjectAction()
    {
        // TODO - Finalize
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
     * Display the specified resource for users
     *
     * @return Response
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
     * @return Response
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
     * @return Response
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
     * @return Response
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

        $users = Developer::select('id', 'username', 'first_name', 'last_name', 'is_active')
                            ->join('user', 'user.id', '=', 'developer.id_user')
                            ->where('developer.is_active', 'true')
                            ->whereNotIn('user.id', $membersIds)
                            ->get();

        return View('pages.admin.adminManageTeam', ['users' => $users, 'team' => $team]);
    }

    /**
     * Edits an already created resource: team
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function editTeamAction(Request $request, $id)
    {
        if(!Auth::user()->isAdmin())
            return redirect()->route('404');

        $id_leader = $request->input('id_leader');

        if(Team::where('id_leader',intVal($id_leader))->whereNotIn('id',[$id])->exists()){
            $leader = User::find($id_leader);
            $message = "ERROR: ". $leader->first_name." ". $leader->last_name." is the leader of an existing team.";
            return redirect()->back()->withErrors($message);
        }

        $team = Team::find($id);
        $team->name = $request->input('name');
        $team->skill = $request->input('skill');
        $team->id_leader = $id_leader;
        $team->save();

        $members = explode(',', $request->input('members'));
        if($members[0] == "")
            $members = [];
            
        array_push($members, $id_leader);

        foreach ($members as $id) {
            if(Team::where('id_leader',intVal($id))->whereNotIn('id',[$team->id])->exists()){
                $member = User::find($id);
                $message = "ERROR: ". $member->first_name." ". $member->last_name." is the leader of an existing team.";
                return redirect()->back()->withErrors($message);
            }

            $member = Developer::find($id);
            $member->id_team = $team->id;
            $member->save();
        }

        $old_members = Developer::where('id_team',$team->id)->whereNotIn('id_user', $members)->get();

        foreach ($old_members as $member) {
            $member->id_team = null;
            $member->save();
        }

        return redirect()->route('admin-teams');
    }

    /**
     * Show the form for editing the specified resource: project
     *
     * @param  int  $id
     * @return Response
     */
    public function editProject($id)
    {
        $project = Project::find($id);

        if(!Auth::user()->isAdmin() || empty($project))
            return redirect()->route('404');

        $project['manager'] = User::where('id', $project->id_manager)->first();

        return View('pages.admin.adminManageProject', ['project' => $project]);
    }

    /**
     * Edits a project
     *
     * @param $id
     * @return Factory|RedirectResponse|View
     */
    public function editProjectAction($id)
    {// TODO - Finalize
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
     * Removes a specific user resource
     *
     * @param $id
     * @return ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function removeUser($id)
    {
        if(!Auth::user()->isAdmin())
            return redirect()->route('404');

        try {
            $user = Developer::find($id);
            $user->is_active = false;
            $user->save();
        } catch (Exception $exception) {
            return response('', 400, []);
        }
    }

    /**
     * Restores a specific user resource
     *
     * @param $id
     * @return RedirectResponse
     */
    public function restoreUser($id)
    {
        if(!Auth::user()->isAdmin())
            return redirect()->route('404');

        $user = Developer::find($id);
        $user->is_active = TRUE;
        $user->save();
    }

    /**
     * Removes a specific team resource
     *
     * @param $id
     * @return RedirectResponse
     */
    public function removeTeam($id)
    {
        $team = Team::find($id);

        if(!Auth::user()->isAdmin() || empty($team))
            return redirect()->route('404');

        $team->delete();
    }

    /**
     * Remove a specific project resource
     *
     * @param $id_project
     * @return RedirectResponse
     */
    public function cancelProject($id_project) 
    {
        if(!Auth::user()->isAdmin())
            return redirect()->route('404');

        Project::destroy($id_project);
    }

}
