<?php

namespace App\Http\Controllers;

use App\Developer;
use App\Milestone;
use App\Project;
use App\Task;
use App\TaskGroup;
use App\TeamTask;
use DateTime;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Comment;
use App\TaskComment;
use Illuminate\View\View;


class TaskController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param $id_project
     * @param $id_task
     * @return Response
     */
    public function show($id_project, $id_task)
    {
        $project = Project::find($id_project);
        $task = Task::find($id_task);

        if(!$this->validateAccess('view', $project, $task))
            return redirect()->route('404');

        $teams = $task->teams;
        $isTeamLeader = false;

        foreach ($teams as $team) {
            $team['isTeamLeader'] = $team->leader->id == Auth::user()->getAuthIdentifier();
            $isTeamLeader = $team->leader->id == Auth::user()->getAuthIdentifier() || $isTeamLeader;
        }

        $isProjectManager = Project::isProjectManager($project);

        return View('pages.task.task', ['project' => $project,
                                        'isProjectManager' => $isProjectManager,
                                        'isTeamLeader' => $isTeamLeader,
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
     * @return Response
     * @throws Exception
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

    /**
     * Edits the specified resource
     *
     * @param $id_project
     * @param $id_task
     * @return RedirectResponse
     */
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
        } catch (Exception $exception) {
            $message = "ERROR: A task with the same name already exists in this project.";
            return redirect()->back()->withErrors($message);
        }
        
        return redirect()->route('task', ['id_project' => $project->id, 'id_task' => $task->id ]);
    }

    /**
     * Deletes the specified resource
     *
     * @param $id_project
     * @param $id_task
     * @return RedirectResponse
     */
    public function delete($id_project, $id_task) {
        $project = Project::find($id_project);
        $task = Task::find($id_task);

        if(!$this->validateAccess('delete', $project, $task))
            return redirect()->route('404');
            
        $task->delete();
    }

    /**
     * Updates task progress
     *
     * @param Request $request
     * @param $id_project
     * @param $id_task
     * @return RedirectResponse
     */
    public function updateProgress(Request $request, $id_project, $id_task) {
        $project = Project::find($id_project);
        $task = Task::find($id_task);

        if(!$this->validateAccess('update', $project, $task))
            return redirect()->route('404');

        $task->progress = $request->input('progress');
        $task->save();
    }

    /**
     * Display the page responsible for task assign
     *
     * @param $id_project
     * @param $id_task
     * @return Factory|RedirectResponse|View
     * @throws Exception
     */
    public function showAssign($id_project, $id_task) {

        $project = Project::find($id_project);
        $task = Task::find($id_task);

        if(!$this->validateAccess('assign', $project, $task))
            return redirect()->route('404');

        $milestones = [];
        $currentMilestone = $task->milestone;

        foreach ($project->milestones as $milestone) {
            if(($currentMilestone == null || $currentMilestone->id != $milestone->id) && $milestone->deadline >= (new DateTime())->format('Y-m-d'))
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

    /**
     * Assign task to teams and milestones
     *
     * @param Request $request
     * @param $id_project
     * @param $id_task
     * @return RedirectResponse
     */
    public function assign(Request $request, $id_project, $id_task) {
        $project = Project::find($id_project);
        $task = Task::find($id_task);

        if(!$this->validateAccess('assign', $project, $task))
            return redirect()->route('404');
        
        $new_teams = explode(',', $request->input('teams'));

        if($new_teams[0] == "")
            $new_teams = [];

        foreach ($new_teams as $new_team) {
            if(!TeamTask::where([['id_team',$new_team],['id_task',$task->id]])->exists()) {
                $team_task = new TeamTask();
                $team_task->id_team = intval($new_team);
                $team_task->id_task = $task->id;
                $team_task->save();
            }
        }

        TeamTask::where('id_task', $id_task)->whereNotIn('id_team', $new_teams)->delete();

        $id_milestone = $request->input('milestone');
        $milestone = Milestone::find($id_milestone);

        if(empty($milestone) || $milestone->id_project != $project->id)
            return redirect()->route('404');

        $task->id_milestone = $milestone->id;
        $task->save();

        return Task::information([$task])[0];
    }

    /**
     * Changes task current task group for a specific one
     *
     * @param $id_project
     * @param $id_task
     * @param null $id_group
     * @return RedirectResponse
     */
    public function group($id_project, $id_task, $id_group = null) {
        $project = Project::find($id_project);
        $task = Task::find($id_task);
        $group = $id_group == null? null : TaskGroup::find($id_group);

        try {
            if(empty($project) || empty($task))
                throw new AuthorizationException();

            $this->authorize('group', [$task , $project, $group]);
        }
        catch (AuthorizationException $e) {
            return redirect()->route('404');
        }

        $task->id_group = $group == null? null : $group->id;
        $task->save();
    }

    /**
     * Adds a new tasks comment to discussion section
     *
     * @param Request $request
     * @param $id_project
     * @param $id_task
     * @return Comment|ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
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
        $comment->author_name = Auth::user()->first_name.' '.Auth::user()->last_name;
        $comment->id_task = $id_task;

        $task_comment = new TaskComment();
        $task_comment->id_comment = $comment->id;
        $task_comment->id_task = $id_task;
        $task_comment->save();

        return $comment;
    }

    /**
     * Edits task comment
     *
     * @param Request $request
     * @param $id_project
     * @param $id_task
     * @param $id_comment
     * @return ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
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
     * @return ResponseFactory|\Symfony\Component\HttpFoundation\Response
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
     * Validates access for task related operations
     *
     * @param $action
     * @param $project
     * @param $task
     * @return bool
     */
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
