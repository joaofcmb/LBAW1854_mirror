<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Forum;
use App\Project;
use App\Thread;
use App\ThreadComment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpParser\Node\Expr\Cast\Object_;

class ThreadController extends Controller
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
     * Display the specified resource for company forum thread.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $thread = Thread::find($id);
        $threadInformation = Thread::threadInformation([$thread])[0];
        $threadComments = Comment::commentInformation(Thread::find($id)->comments);

        return View('pages.forum.thread', ['thread' => $threadInformation, 'comments' => $threadComments, 'isProjectForum' => false]);
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
        $thread = Thread::find($id_thread);
        $threadInformation = Thread::threadInformation([$thread])[0];
        $threadComments = Comment::commentInformation(Thread::find($id_thread)->comments);

        return View('pages.forum.thread', ['project' => $project, 'thread' => $threadInformation, 'comments' => $threadComments, 'isProjectForum' => true]);
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
