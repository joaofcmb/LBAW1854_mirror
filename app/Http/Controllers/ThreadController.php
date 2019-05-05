<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Forum;
use App\Project;
use App\Thread;
use App\ThreadComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * Create a new resource for this page
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = isset($_POST['title']) ? htmlentities($_POST['title']) : '';
        $description = isset($_POST['description']) ? htmlentities($_POST['description']) : '';

        if($title === '' || $description === '')
            return redirect()->route('company-forum-create-thread-action');

        $thread = new Thread();

        $thread->title = $title;
        $thread->description = $description;
        $thread->id_author = Auth::user()->getAuthIdentifier();
        $thread->id_forum = 1;

        $thread->save();

        return redirect()->route('companyforum-thread', ['id_thread' => $thread->id]);
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
        if(!Thread::where([['id_forum', 1], ['id', $id]])->exists())
            return redirect()->route('404');

        $thread = Thread::select('thread.id', 'title', 'description', 'id_author', 'id_forum', 'username as author_name')
            ->join('user', 'user.id', '=', 'thread.id_author')
            ->where('thread.id', $id)
            ->first();

        return View('pages.forum.thread', ['thread' => $thread, 'comments' => Comment::information(Thread::find($id)->comments), 'isProjectForum' => false]);
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
    public function delete(Request $request, $id)
    {

    }
}
