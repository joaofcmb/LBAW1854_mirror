<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Thread;
use App\ThreadComment;
use DateTime;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    /**
     * Create a new resource for this page
     *
     * @return Response
     */
    public function create()
    {
        $title = isset($_POST['title']) ? htmlentities($_POST['title']) : '';
        $description = isset($_POST['description']) ? htmlentities($_POST['description']) : '';

        if($title === '' || $description === '')
            return redirect()->route('companyforum');

        $thread = new Thread();

        $thread->title = $title;
        $thread->description = $description;
        $thread->id_author = Auth::user()->getAuthIdentifier();
        $thread->id_forum = 1;

        $thread->save();

        return redirect()->route('companyforum-thread', ['id_thread' => $thread->id]);
    }

    /**
     * Adds a new comment
     *
     * @param Request $request
     * @param $id_thread
     * @return Comment
     */
    public function addComment(Request $request, $id_thread)
    {
        if(!Thread::where([['id', $id_thread], ['id_forum', 1]])->exists())
            return response("", 404, []);

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

    /**
     * Display the specified resource for company forum thread.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        if(!Thread::where([['id_forum', 1], ['id', $id]])->exists())
            return redirect()->route('404');

        $thread = Thread::select('thread.id', 'title', 'description', 'id_author', 'id_forum', 'username as author_name', 'first_name', 'last_name')
            ->join('user', 'user.id', '=', 'thread.id_author')
            ->where('thread.id', $id)
            ->first();

        return View('pages.forum.thread', ['thread' => $thread, 'comments' => Comment::information(Thread::find($id)->comments), 'isProjectForum' => false]);
    }

    /**
     * Edits a certain comment
     *
     * @param Request $request
     * @param $id_thread
     * @param $id_comment
     * @return ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws Exception
     */
    public function editComment(Request $request, $id_thread, $id_comment)
    {
        $thread = Thread::find($id_thread);
        $comment = Comment::find($id_comment);

        if(empty($thread) || empty($comment) || $thread->id_forum != 1 || $comment->id_author != Auth::user()->getAuthIdentifier())
            return response("", 404, []);

        $comment['text'] = $request->input('text');

        $date = new DateTime();
        $comment->last_edit_date = $date->getTimestamp();

        $comment->save();
    }

    /**
     * Remove the specified resource (thread) from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function delete($id)
    {
        $thread = Thread::find($id);

        try {
            if(empty($thread))
                throw new AuthorizationException();

            $this->authorize('delete', $thread);
        }
        catch (AuthorizationException $e) {
            return response("", 404, []);
        }

        ThreadComment::where('id_thread',$id)->delete();

        $thread->delete();
    }

    /**
     * Deletes a company forum thread comment
     *
     * @param $id_thread
     * @param $id_comment
     * @return ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteComment($id_thread, $id_comment)
    {
        $thread = Thread::find($id_thread);
        $comment = Comment::find($id_comment);
        $thread_comment = ThreadComment::where([['id_comment', $id_comment],['id_thread', $id_thread]])->get();

        if(empty($thread) || empty($comment) || empty($thread_comment) || $thread->id_forum != 1 || $comment->id_author != Auth::user()->getAuthIdentifier())
            return response("", 404, []);

        ThreadComment::where([['id_comment', $id_comment],['id_thread', $id_thread]])->delete();
    }
}
