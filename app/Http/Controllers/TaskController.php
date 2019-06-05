<?php

namespace App\Http\Controllers;

use App\Developer;
use App\Milestone;
use App\Project;
use App\Task;
use App\TaskGroup;
use App\Team;
use App\TeamTask;
use App\User;
use DateTime;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Comment;
use App\TaskComment;

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

        $isProjectManager = Project::isProjectManager($project);

        return View('pages.task.task', ['project' => $project,
                                        'isProjectManager' => $isProjectManager,
                                        'teams' => $teams,
                                        'comments' => $task->comments,
                                        'canAddComment' => Developer::canAddTaskComment($task) || $isProjectManager,
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

    public function editAction($id_project, $id_task)
    {
        $project = Project::find($id_project);
        $task = Task::find($id_task);

        if(!$this->validateAccess('edit', $project, $task))
            return redirect()->route('404');

        $name = isset($_POST['name']) ? htmlentities($_POST['name']) : '';
        $description = isset($_POST['description']) ? htmlentities($_POST['description']) : '';

        if($name === '' || $description === '')
            return redirect()->route('task-edit', ['id' => $project->id, 'id_task' => $id_task]);
        
        $task->title = $name;
        $task->description = $description;

        try {
            $task->save();
        } catch (\Exception $exception) {
            $message = "ERROR: A task with the same name already exists in this project.";
            return redirect()->back()->withErrors($message);
        }
        
        return redirect()->route('task', ['id_project' => $project->id, 'id_task' => $task->id ]);
    }

    public function delete($id_project, $id_task) {
        $project = Project::find($id_project);
        $task = Task::find($id_task);

        if(!$this->validateAccess('delete', $project, $task))
            return redirect()->route('404');
        
        TaskComment::where('id_task',$id_task)->delete();

        $task->delete();
    }

    public function updateProgress(Request $request, $id_project, $id_task) {
        $project = Project::find($id_project);
        $task = Task::find($id_task);

        if(!$this->validateAccess('edit', $project, $task))
            return redirect()->route('404');

        $task->progress = $request->input('progress');
        $task->save();
    }

    public function assign($id_project, $id_task) {

        $project = Project::find($id_project);
        $task = Task::find($id_task);

        if(!$this->validateAccess('assign', $project, $task))
            return redirect()->route('404');

        $milestones = [];
        $currentMilestone = $task->milestone;

        foreach ($project->milestones as $milestone) {
            if(($currentMilestone->id == null || $currentMilestone->id != $milestone->id) && $milestone->deadline >= (new DateTime())->format('Y-m-d'))
                array_push($milestones, $milestone);
        }

        return View('pages.task.taskAssign', ['project' => $project,
                                                    'isProjectManager' => Project::isProjectManager($project),
                                                    'tasks' => Task::information($project->tasks),
                                                    'selectedTask' => Task::information([$task])[0],
                                                    'milestones' => $milestones,
                                                    'currentMilestone' => $currentMilestone
        ]);
    }

    public function group($id_project, $id_task, $id_group) {
        $project = Project::find($id_project);
        $task = Task::find($id_task);
        $group = TaskGroup::find($id_group);

        if(!$this->validateAccess('group', $project, $task, $group))
            return redirect()->route('404');

        $task->id_group = $id_group;
        $task->save();
    }

    public function addTaskComment(Request $request, $id_project, $id_task)
    {
        $project = Project::find($id_project);
        $task = Task::find($id_task);

        if(!$this->validateAccess('addComment', $project, $task))
            return response('', 404, []);

        $text = $request->input('text');

        $comment = new Comment();

        $comment['text'] = $text;
        $comment->id_author = Auth::user()->getAuthIdentifier();
        $comment->save();
        $comment->author_name = Auth::user()->username;
        $comment->id_task = $id_task;

//CORRIGIR ERRO
        $task_comment = new TaskComment();
        $task_comment->id_comment = $comment->id;
        $task_comment->id_task = $id_task;
        $task_comment->save();

        return $comment;
    }

    public function editTaskComment(Request $request, $id_project, $id_task, $id_comment)
    {
        $project = Project::find($id_project);
        $task = Task::find($id_task);
        $comment = Comment::find($id_comment);

        if(empty($project) || empty($task) || empty($comment) || 
            ($comment->id_author != Auth::user()->getAuthIdentifier() && $project->id_manager != Auth::user()->getAuthIdentifier()))
            return response('', 404, []);

        $comment['text'] = $request->input('text');
        $comment->save();
    }

    /**
     * Deletes a task comment
     *
     * @param $id_project
     * @param $id_task
     * @param $id_comment
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteTaskComment($id_project, $id_task, $id_comment) {

        $project = Project::find($id_project);
        $task = Task::find($id_task);
        $comment = Comment::find($id_comment);
        $task_comment = TaskComment::where([['id_comment', $id_comment],['id_task', $id_task]])->get();

        try {
            if(empty($project) || empty($task) || empty($comment) || empty($task_comment))
                return response('', 404, []);

            $this->authorize('deleteTaskComment', [$task, $project, $comment]);
        }
        catch (AuthorizationException $e) {
            return response('', 404, []);
        }
        
        TaskComment::where([['id_comment', $id_comment],['id_task', $id_task]])->delete();
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
