<?php

namespace App\Http\Controllers;

use App\Forum;
use App\Project;
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
        $milestones = $project->milestones->where('deadline', '>', $date)->first();

      // echo $milestones;
        //echo $project->milestones . '<br>' . $currentDate . '<br>' . $project->milestones->where('deadline', '<', $currentDate);

        //die();

        return View('pages.project.projectOverview', ['project' => $projectInformation, 'forum' => $forum, 'threads' => $threads]);
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
