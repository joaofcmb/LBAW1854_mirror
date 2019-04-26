<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Forum;
use App\Milestone;
use App\Project;
use App\Task;
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
     * Display the specified resource for project overview
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function show($id)
    {
        $project = Project::find($id);

        if(!$this->validateAccess($project))
            return redirect()->route('404');

        $projectInformation = Project::projectInformation($project);

        $forum = $project->forum;
        $threads = Thread::threadInformation($forum->threads->take(7));

        $currentDate = new DateTime();
        $date = $currentDate->format('Y-m-d');

        $milestones = Milestone::where('id_project', $project->id)->orderBy('deadline', 'asc')->limit(6)->get();
        $currentMilestone = Milestone::where([['id_project', $project->id], ['deadline', '>=', $date]])->orderBy('deadline', 'asc')->first();
        $currentMilestone['timeLeft'] = $currentDate->diff(new DateTime($currentMilestone->deadline))->format('%a');

        $isProjectManager = $project->id_manager == Auth::user()->getAuthIdentifier();

        return View('pages.project.projectOverview', ['project' => $projectInformation,
                                                        'isProjectManager' => $isProjectManager,
                                                        'milestones' => $milestones,
                                                        'currentMilestone' => $currentMilestone,
                                                        'forum' => $forum,
                                                        'threads' => $threads,
                                                        'date' => $date
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

        if(!$this->validateAccess($project))
            return redirect()->route('404');

        $projectInformation = Project::projectInformation($project);

        $currentDate = new DateTime();
        $date = $currentDate->format('Y-m-d');

        $milestones = Milestone::where('id_project', $project->id)->orderBy('deadline', 'asc')->limit(6)->get();

        foreach ($milestones as $milestone) {
            $milestone['timeLeft'] = $currentDate->diff(new DateTime($milestone->deadline))->format('%a');
        }

        $currentMilestone = Milestone::where([['id_project', $project->id], ['deadline', '>=', $date]])->orderBy('deadline', 'asc')->first();
        $currentMilestone['timeLeft'] = $currentDate->diff(new DateTime($currentMilestone->deadline))->format('%a');

        $currentMilestoneTasks = Task::cardInformation(Task::where([['id_milestone', $currentMilestone->id], ['progress', '<', 100]])->get());

        $isProjectManager = $project->id_manager == Auth::user()->getAuthIdentifier();

        return View('pages.project.projectRoadmap', ['project' => $projectInformation,
                                                        'isProjectManager' => $isProjectManager,
                                                        'milestones' => $milestones,
                                                        'currentMilestone' => $currentMilestone,
                                                        'currentMilestoneTasks' => $currentMilestoneTasks,
                                                        'date' => $date
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

        if(!$this->validateAccess($project))
            return redirect()->route('404');

        $projectInformation = Project::projectInformation($project);

        $projectUngroupedTasks = Task::cardInformation(Task::where('id_group', null)->get());

        $projectTaskGroups = $project->taskGroups;
        foreach($projectTaskGroups as $taskGroup) {
            $taskGroup['tasks'] = Task::cardInformation($taskGroup->tasks);
        }

        $isProjectManager = $project->id_manager == Auth::user()->getAuthIdentifier();

        return View('pages.project.projectTasks', ['project' => $projectInformation,
                                                         'projectUngroupedTasks' => $projectUngroupedTasks,
                                                         'projectTaskGroups' => $projectTaskGroups,
                                                         'isProjectManager' => $isProjectManager
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

        if(!$this->validateAccess($project))
            return redirect()->route('404');

        $threads = Thread::threadInformation(Forum::find($project->forum->id)->threads);
        $isProjectManager = $project->id_manager == Auth::user()->getAuthIdentifier();

        return View('pages.forum.forum', ['project' => $project, 'threads' => $threads, 'isProjectManager' => $isProjectManager, 'isProjectForum' => true]);
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

        if(!$this->validateAccess($project))
            return redirect()->route('404');

        $thread = Thread::find($id_thread);

        if(!Thread::where([['id_forum', $project->forum->id], ['id', $id_thread]])->exists())
            return redirect()->route('404');

        $threadInformation = Thread::threadInformation([$thread])[0];
        $threadComments = Comment::commentInformation(Thread::find($id_thread)->comments);

        $isProjectManager = $project->id_manager == Auth::user()->getAuthIdentifier();

        return View('pages.forum.thread', ['project' => $project, 'thread' => $threadInformation,
            'comments' => $threadComments, 'isProjectManager' => $isProjectManager, 'isProjectForum' => true]);
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

    public function validateAccess($project)
    {
        try {
            if(empty($project))
                throw new AuthorizationException();

            $this->authorize('view', $project);
        }
        catch (AuthorizationException $e) {
            return false;
        }

        return true;
    }
}
