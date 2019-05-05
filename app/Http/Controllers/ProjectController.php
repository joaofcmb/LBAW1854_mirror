<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Forum;
use App\Milestone;
use App\Project;
use App\Task;
use App\TaskGroup;
use App\Thread;
use DateTime;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function addThreadComment(Request $request, $id_project, $id_thread) {

    }

    public function changeMilestoneView(Request $request, $id_project)
    {
        $project = Project::find($id_project);

        if(!$this->validateAccess($project, 'view'))
            return response("", 404, []);

        $response = Project::getMilestone($request->input('milestone'));

        return json_encode($response);
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
            return response("", 404, []);
        }

        $thread->delete();
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
