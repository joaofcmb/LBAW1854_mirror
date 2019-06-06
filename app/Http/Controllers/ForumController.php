<?php

namespace App\Http\Controllers;

use App\Forum;
use App\Project;
use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    /**
     * Display company forum view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showCompanyForum()
    {
        $companyForum = Forum::find(1);

        return View('pages.forum.forum', ['threads' => $companyForum->threads, 'isProjectForum' => false]);
    }
}
