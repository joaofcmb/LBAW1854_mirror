@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/thread.css') }}" rel="stylesheet">
@endsection

@section('title', 'Company Forum')

@section('body')
<body class="bg-light">
    @include('partials.main-navbar', [
        'items' =>
            [
                ['name' => 'HOME', 'route' => route('home')],
                ['name' => 'SEARCH', 'route' => ''],
                ['name' => 'FORUM', 'route' => route('companyforum'), 'active' => true],
                ['name' => 'PROFILE', 'route' => route('profile', ['id' => Auth::user()->getAuthIdentifier()])],
            ],
        'auth' => 'session'
     ])
    @if($isProjectForum)
        <nav id="sub-menu" class="navbar navbar-expand-sm p-0 pr-3 pr-sm-1 pr-lg-3">
            <a class="navbar-brand h-5 px-3 py-2" style="color: white; background-color: rgb(12, 116, 214);">Company
                Website</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent2"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse py-2 py-sm-0" id="navbarSupportedContent2">
                <a class="nav-item nav-link mx-lg-3" href="{{ route('project-overview', ['id_project' => $project->id]) }}">Overview</a>
                <a class="nav-item nav-link mx-lg-3" href="project-roadmap.html">Roadmap</a>
                <a class="nav-item nav-link mx-lg-3" href="project-tasks.html">Tasks</a>
                <a class="nav-item nav-link active mx-lg-3" href="{{ route('project-forum', ['id_project' => $project->id]) }}">Forum</a>

                <a class="text-danger font-weight-bolder ml-3 ml-sm-auto" href="" style="text-decoration: none;"><i
                            class="fas fa-times"></i><span class="d-sm-none d-md-inline"> Close Project</span></a>
            </div>
        </nav>
    @endif

    <div id="menu-option" class="justify-content-start container py-4">
        <a href="{{ $isProjectForum ? route('project-forum', ['id' => $project->id]) : route('companyforum') }}"><i class="fas fa-chevron-circle-left mx-2"></i>Back</a>
    </div>
    <div id="thread-content" class="container px-3 mb-5">
        <div class="card my-3 p-3">
            <h4>{{ $thread->title }}</h4>
            <a class="d-flex flex-row pt-1" href="{{ route('profile', ['id' => $thread->id_author]) }}">
                <i class="fas fa-user mr-1"></i>
                <h6>{{ $thread->author_name }}</h6>
            </a>
            <p class="mt-2">{{ $thread->description }}<p>
        </div>
        @foreach($comments as $comment)
            <div class="card p-3 my-3">
                <div class="row">
                    <div class="col">
                        <a class="d-flex flex-row pt-1" href="{{ route('profile', ['id' => $comment->id_author]) }}">
                            <i class="fas fa-user mr-1"></i>
                            <h6>{{ $comment->author_name }}</h6>
                        </a>
                    </div>
                    <div class="col text-right">
                        @if($comment->id_author === Auth::user()->getAuthIdentifier())
                            <a href=""><i class="fas fa-pen mx-3"></i></a>
                        @endif
                        @if($comment->id_author === Auth::user()->getAuthIdentifier() || Auth::user()->isAdmin())
                            <a href=""><i class="fas fa-trash-alt mx-2"></i></a>
                        @endif
                    </div>
                </div>
                <p class="mt-2">{{ $comment->text }}<p>
            </div>
        @endforeach
        <form>
            <div class="form-row">
                <div class="form-group col-md-12 my-3">
                    <input type="text" class="form-control" id="threadName" placeholder="Add your comment ...">
                </div>
            </div>
        </form>
    <footer class="fixed-bottom p-1 pl-2">
        COPYRIGHT © EPMA 2019
    </footer>
</body>
@endsection