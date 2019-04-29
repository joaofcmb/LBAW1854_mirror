<?php

namespace App\Http\Controllers;

use App\Developer;
use App\Project;
use App\Task;
use App\Team;
use App\TeamTask;
use App\User;
use DateTime;
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
            $team['isTeamLeader'] = $team->leader->id == Auth::user()->getAuthIdentifier();
        }


        return View('pages.task.task', ['project' => $project,
                                              'isProjectManager' => Project::isProjectManager($project),
                                              'teams' => $teams,
                                              'comments' => $task->comments,
                                              'canAddComment' => Developer::canAddTaskComment($task),
                                              'task' => Task::information([$task])[0]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id_project
     * @param $id_task
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function edit($id_project, $id_task)
    {
        $project = Project::find($id_project);
        $task = Task::find($id_task);

        if(!$this->validateAccess('edit', $project, $task))
            return redirect()->route('404');

        $milestones = [];
        $currentMilestone = Project::getCurrentMilestone($project);

        foreach ($project->milestones as $milestone) {
            if($currentMilestone->id != $milestone->id && $milestone->deadline >= (new DateTime())->format('Y-m-d'))
                array_push($milestones, $milestone);
        }

        return View('pages.task.taskEdit', ['project' => $project,
                                                  'isProjectManager' => Project::isProjectManager($project),
                                                  'teams' => $task->teams,
                                                  'milestones' => $milestones,
                                                  'currentMilestone' => $currentMilestone,
                                                  'task' => Task::information([$task])[0]
        ]);
    }

    public function assign($id_project, $id_task) {

        $project = Project::find($id_project);
        $task = Task::find($id_task);

        if(!$this->validateAccess('assign', $project, $task))
            return redirect()->route('404');


        return View('pages.task.taskAssign', ['project' => $project,
                                                    'isProjectManager' => Project::isProjectManager($project)

        ]);
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
