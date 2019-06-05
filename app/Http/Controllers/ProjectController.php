<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Forum;
use App\Milestone;
use App\Project;
use App\Task;
use App\TaskGroup;
use App\Thread;
use App\ThreadComment;
use DateTime;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProjectController extends Controller
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
     * Show the form for creating a new resource: task.
     *
     * @return \Illuminate\Http\Response
     */
    public function createTask($id_project, $id_taskgroup = 0)
    {
        $project = Project::find($id_project);
        $taskGroup = $id_taskgroup != 0 ? TaskGroup::find($id_taskgroup) : null;

        if(!$this->validateAccess($project, 'createTask') || ($taskGroup != null && (empty($taskGroup) || $taskGroup->id_project != $project->id)))
            return redirect()->route('404');

        return View('pages.task.taskCreate', ['project' => $project,
                                                    'isProjectManager' => Project::isProjectManager($project),
                                                    'id_taskgroup' => $id_taskgroup
        ]);
    }

    public function createTaskAction($id_project, $id_taskgroup = 0)
    {
        $project = Project::find($id_project);
        $taskGroup = $id_taskgroup != 0 ? TaskGroup::find($id_taskgroup) : null;

        if(!$this->validateAccess($project, 'createTask') || ($taskGroup != null && (empty($taskGroup) || $taskGroup->id_project != $project->id)))
            return redirect()->route('404');

        $name = isset($_POST['name']) ? htmlentities($_POST['name']) : '';
        $description = isset($_POST['description']) ? htmlentities($_POST['description']) : '';

        if($name === '' || $description === '')
            return redirect()->route('task-create', ['id' => $project->id ($id_taskgroup != 0 ? ", 'id_taskgroup' => $id_taskgroup" : '')]);
        
        $task = new Task();
        $task->title = $name;
        $task->description = $description;
        $task->id_project = $id_project;
        if($id_taskgroup != 0)
            $task->id_group = $id_taskgroup;

        try {
            $task->save();
        } catch (\Exception $exception) {
            $message = "ERROR: A task with the same name already exists in this project.";
            return redirect()->back()->withErrors($message);
        }
        
        return redirect()->route('task', ['id_project' => $project->id, 'id_task' => $task->id ]);
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
     * Display the specified resource for project overview
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function show($id)
    {
        $project = Project::find($id);

        if(!$this->validateAccess($project, 'view'))
            return redirect()->route('404');

        return View('pages.project.projectOverview', ['isProjectManager' => Project::isProjectManager($project),
                                                            'canCreateThread' => $this->validateAccess($project, 'createThread'),
                                                            'milestones' => $project->milestones,
                                                            'currentMilestone' => Project::getCurrentMilestone($project),
                                                            'forum' => $project->forum,
                                                            'threads' => $project->forum->threads->take(7),
                                                            'date' => (new DateTime())->format('Y-m-d'),
                                                            'project' => Project::information([$project])[0]
        ]);
    }

    /**
     * Display the specified resource for project roadmap
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function showRoadmap($id) {

        $project = Project::find($id);

        if(!$this->validateAccess($project, 'view'))
            return redirect()->route('404');

        return View('pages.project.projectRoadmap', ['isProjectManager' => Project::isProjectManager($project),
                                                           'milestones' => Milestone::information($project->milestones),
                                                           'currentMilestone' => Project::getCurrentMilestone($project),
                                                           'date' => (new DateTime())->format('Y-m-d'),
                                                           'project' => Project::information([$project])[0]
        ]);
    }

    /**
     * Display the specified resource for project roadmap
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showTasks($id) {

        $project = Project::find($id);

        if(!$this->validateAccess($project, 'view'))
            return redirect()->route('404');

        $unGroupedTasks = Task::information(Task::where([['id_group', null],['id_project', $project->id]])->get());
        $projectTaskGroups = $project->taskGroups;

        foreach($projectTaskGroups as $taskGroup) {
            $taskGroup['tasks'] = Task::information($taskGroup->tasks);
        }

        return View('pages.project.projectTasks', ['project' => Project::information([$project])[0],
                                                         'projectUngroupedTasks' => $unGroupedTasks,
                                                         'projectTaskGroups' => $projectTaskGroups,
                                                         'isProjectManager' => Project::isProjectManager($project)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showForum($id)
    {
        $project = Project::find($id);

        if(!$this->validateAccess($project, 'view'))
            return redirect()->route('404');

        return View('pages.forum.forum', ['project' => $project,
                          'threads' => Forum::find($project->forum->id)->threads,
                          'isProjectManager' => Project::isProjectManager($project),
                          'canCreateThread' => $this->validateAccess($project, 'createThread'),
                          'isProjectForum' => true]);
    }

    /**
     * Display the specified resource for project forum thread
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showForumThread($id_project, $id_thread)
    {
        $project = Project::find($id_project);

        if(!$this->validateAccess($project, 'view') || !Thread::where([['id_forum', $project->forum->id], ['id', $id_thread]])->exists())
            return redirect()->route('404');

        $thread = Thread::select('thread.id', 'title', 'description', 'id_author', 'id_forum', 'username as author_name')
            ->join('user', 'user.id', '=', 'thread.id_author')
            ->where('thread.id', $id_thread)
            ->first();

        return View('pages.forum.thread', ['project' => $project,
                                                 'thread' => $thread,
                                                 'comments' => Comment::information(Thread::find($id_thread)->comments),
                                                 'isProjectManager' => Project::isProjectManager($project),
                                                 'canAddComment' => $this->validateAccess($project, 'createThread'),
                                                 'isProjectForum' => true]);
    }

    /**
     * Create forum thread page
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function createForumThread($id)
    {
        $project = Project::find($id);

        if(!$this->validateAccess($project, 'createThread'))
            return redirect()->route('404');

        return View('pages.forum.createThread', ['project' => $project,
                                                       'isProjectManager' => Project::isProjectManager($project),
                                                       'isProjectForum' => true]);

    }

    /**
     * Creates a new forum thread for this resource
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createForumThreadAction($id)
    {
        $project = Project::find($id);

        if(!$this->validateAccess($project, 'createThread'))
            return redirect()->route('404');

        $title = isset($_POST['title']) ? htmlentities($_POST['title']) : '';
        $description = isset($_POST['description']) ? htmlentities($_POST['description']) : '';

        if($title === '' || $description === '')
            return redirect()->route('forum-create-thread', ['id' => $project->id]);

        $thread = new Thread();

        $thread->title = $title;
        $thread->description = $description;
        $thread->id_author = Auth::user()->getAuthIdentifier();
        $thread->id_forum = $project->forum->id;

        $thread->save();

        return redirect()->route('forum-thread', ['id_project' => $id, 'id_thread' => $thread->id]);
    }

    /**
     * Add new comment to a project forum thread
     *
     * @param Request $request
     * @param $id_project
     * @param $id_thread
     * @return Comment|\Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function addThreadComment(Request $request, $id_project, $id_thread)
    {
        $project = Project::find($id_project);

        if(!$this->validateAccess($project, 'addComment') || !Thread::where([['id', $id_thread], ['id_forum', $project->forum->id]])->exists())
            return response('', 404, []);

        $text = $request->input('text');

        $comment = new Comment();

        $comment['text'] = $text;
        $comment->id_author = Auth::user()->getAuthIdentifier();
        $comment->save();
        $comment->author_name = Auth::user()->username;
        $comment->id_thread = $id_thread;

        $thread_comment = new ThreadComment();

        $thread_comment->id_comment = $comment->id;
        $thread_comment->id_thread = $id_thread;
        $thread_comment->save();

        return $comment;
    }

    public function editThreadComment(Request $request, $id_project, $id_thread, $id_comment)
    {
        $project = Project::find($id_project);
        $thread = Thread::find($id_thread);
        $comment = Comment::find($id_comment);

        if(empty($project) || empty($thread) || empty($comment) || 
            ($comment->id_author != Auth::user()->getAuthIdentifier() && $project->id_manager != Auth::user()->getAuthIdentifier()))
            return response('', 404, []);

        $comment['text'] = $request->input('text');
        $comment->save();
    }

    /**
     * Deletes a project forum thread comment
     *
     * @param $id_project
     * @param $id_thread
     * @param $id_comment
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteThreadComment($id_project, $id_thread, $id_comment) {

        $project = Project::find($id_project);
        $thread = Thread::find($id_thread);
        $comment = Comment::find($id_comment);
        $thread_comment = ThreadComment::where([['id_comment', $id_comment],['id_thread', $id_thread]])->first();

        try {
            if(empty($project) || empty($thread) || empty($comment) || empty($thread_comment))
                return response('', 404, []);

            $this->authorize('deleteForumThreadComment', [$project, $thread, $comment]);
        }
        catch (AuthorizationException $e) {
            return response('', 404, []);
        }
        ThreadComment::where([['id_comment', $id_comment],['id_thread', $id_thread]])->delete();
    }

    public function changeMilestoneView(Request $request, $id_project)
    {
        $project = Project::find($id_project);

        if(!$this->validateAccess($project, 'view'))
            return response('', 400, []);

        $response = Project::getMilestone($request->input('milestone'));

        return json_encode($response);
    }

    public function createMilestone($id_project)
    {
        $project = Project::find($id_project);

        if(!$this->validateAccess($project, 'manager'))
            return redirect()->route('project-roadmap', ['id_project' => $id_project]);
        
        $milestone = new Milestone();
        $milestone->id_project = $project->id;
        $milestone->name = $_POST['name'];
        $milestone->deadline = $_POST['deadline'];

        $milestone->save();

        return redirect()->route('project-roadmap', ['id_project' => $id_project]);
    }

    public function updateMilestone(Request $request, $id_project, $id_milestone)
    {
        $project = Project::find($id_project);
        $milestone = Milestone::find($id_milestone);

        if(!$this->validateAccess($project, 'manager'))
            return response('', 400, []);

        if(empty($milestone))
            return response('', 404, []);        

        $milestone->name = $request->input('name');
        $milestone->deadline = new DateTime($request->input('deadline'));
        $milestone->save();

        $currentDate = new DateTime(date('Y/m/d'));
        $milestone['timeLeft'] = $currentDate->diff(new DateTime($milestone->deadline))->format('%a');
        $milestone['tasks'] = Task::information(Task::where([['id_milestone', $milestone->id], ['progress', '<', 100]])->get());
      

        return json_encode(['isProjectManager' => Project::isProjectManager($project),
                            'milestones' => Milestone::information($project->milestones),
                            'currentMilestone' => $milestone,
                            'date' => (new DateTime())->format('Y-m-d'),
                            'project' => Project::information([$project])[0]]);
    }

    public function deleteMilestone($id_project, $id_milestone)
    {
        $project = Project::find($id_project);
        $milestone = Milestone::find($id_milestone);

        if(!$this->validateAccess($project, 'manager'))
            return response('', 400, []);

        if(empty($milestone))
            return response('', 404, []);

        $milestone->delete();
    }

    public function createTaskGroup(Request $request, $id_project)
    {
        $project = Project::find($id_project);

        if(!$this->validateAccess($project, 'manager'))
            return response('', 400, []);

        $taskGroup = new TaskGroup();
        $taskGroup->id_project = $id_project;  
        $taskGroup->title = $request->input('title');  
        $taskGroup->save();

        return json_encode($taskGroup);
    }

    public function updateTaskGroup(Request $request, $id_project, $id_taskgroup)
    {
        $project = Project::find($id_project);
        $taskGroup = TaskGroup::find($id_taskgroup);

        if(!$this->validateAccess($project, 'manager'))
            return response('', 400, []);

        if(empty($taskGroup) || $taskGroup->id_project != $id_project)
            return response('', 404, []); 

        $taskGroup->title = $request->input('title');        
        $taskGroup->save();
    }

    public function deleteTaskGroup($id_project, $id_taskgroup) 
    {
        $project = Project::find($id_project);
        $taskGroup = TaskGroup::find($id_taskgroup);

        if(!$this->validateAccess($project, 'manager'))
            return response('', 400, []);

        if(empty($taskGroup))
            return response('', 404, []);

        $taskGroup->delete();
    }

    /**
     * Remove the specified thread resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteForumThread($id_project, $id_thread)
    {
        $project = Project::find($id_project);
        $thread = Thread::find($id_thread);

        try {
            if(empty($project) || empty($thread))
                throw new AuthorizationException();

            $this->authorize('deleteForumThread', [$project, $thread]);
        }
        catch (AuthorizationException $e) {
            return response('', 404, []);
        }

        $thread->delete();
    }

    public function closeProject($id_project) 
    {
        $project = Project::find($id_project);
        
        if(empty($project) || Project::isLocked($project))
            return redirect()->route('404');

        if($project->id_manager != Auth::user()->getAuthIdentifier())
            return redirect()->route('project-overview', ['id', $id_project]);

        $project->status = 'closed';
        $project->save();

        return redirect()->route('home');
    }

    public function validateAccess($project, $action)
    {
        try {
            if(empty($project))
                throw new AuthorizationException();

            $this->authorize($action, $project);
        }
        catch (AuthorizationException $e) {
            return false;
        }

        return true;
    }
}
