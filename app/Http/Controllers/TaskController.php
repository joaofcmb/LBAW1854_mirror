<?php

namespace App\Http\Controllers;

use App\Developer;
use App\Project;
use App\Task;
use App\Team;
use App\TeamTask;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_project, $id_task)
    {
        $project = Project::find($id_project);
        $task = Task::find($id_task);

        if(!$this->validateAccess('view', $project, $task))
            return redirect()->route('404');

        $teams = $task->teams;

        foreach ($teams as $team) {
            $teamObject = Team::find($team->id);
            $team['leader'] = $teamObject->leader;
            $team['members'] = $teamObject->members;
            $team['isTeamLeader'] = $team->leader->id == Auth::user()->getAuthIdentifier();
        }

        $comments = $task->comments;
        $canAddComment = Developer::canAddTaskComment($task);

        $task = Task::cardInformation([$task])[0];
        $isProjectManager = $project->id_manager == Auth::user()->getAuthIdentifier();

        return View('pages.task.task', ['project' => $project, 'isProjectManager' => $isProjectManager, 'task' => $task, 'teams' => $teams, 'comments' => $comments, 'canAddComment' => $canAddComment]);
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

    public function validateAccess($action, $project, $task)
    {
        try {
            if(empty($project) || empty($task))
                throw new AuthorizationException();

            $this->authorize($action, [$task , $project]);
        }
        catch (AuthorizationException $e) {
            return false;
        }

        return true;
    }
}
