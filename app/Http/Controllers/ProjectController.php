<?php

namespace App\Http\Controllers;

use App\Forum;
use App\Milestone;
use App\Project;
use App\Task;
use App\Thread;
use DateTime;
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $project = Project::find($id);
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

    public function showRoadmap($id) {

        $project = Project::find($id);
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

    public function showTasks($id) {
        echo "222";
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
}
