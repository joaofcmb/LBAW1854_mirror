@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/thread.css') }}" rel="stylesheet">
@endsection

@section('title')
    @if($isProjectForum)
        Company Forum
    @else
        Project Forum
    @endif
@endsection

@section('body')
    @if($isProjectForum)
        <div class="navbar-dark sticky-top">
    @endif

    @include('partials.main-navbar', [
        'active' => 'forum',
        'isProjectForum' => $isProjectForum,
        'auth' => 'session'
    ])

    @if($isProjectForum)
            @include('partials.sub-navbar', [
                'active' => 'forum',
                'project' => $project,
                'isProjectManager' => $isProjectManager
            ])
        </div>
        <div id="menu-option" class="container-fluid justify-content-start mx-auto py-4">
            <a href="{{ route('project-forum', ['id' => $id_project]) }}"><i class="fas fa-chevron-circle-left mx-2"></i>Back</a>
        </div>
    @else
        <div id="menu-option" class="container-fluid justify-content-start mx-auto py-4">
            <a href="{{ route('companyforum') }}"><i class="fas fa-chevron-circle-left mx-2"></i>Back</a>
        </div>
    @endif

    <div id="thread-content" class="container px-sm-3 mb-5">
        <div class="card my-3 px-3 pt-3">
            <h4>{{ $thread->title }}</h4>
            <a class="d-flex flex-row pt-1" href="{{ route('profile', ['id' => $thread->id_author]) }}">
                <i class="fas fa-user mr-1"></i>
                <h6>{{ $thread->author_name }}</h6>
            </a>
            <p class="mt-2">{{ $thread->description }}</p>
        </div>
        @foreach($comments as $comment)
            @include('partials.cards.comment', ['comment => $comment'])
        @endforeach
        <form>
            @if(!$isProjectForum  || $canAddComment)
                <div class="form-row">
                    <div class="form-group col-md-12 my-3">
                        <input type="text" class="form-control" id="threadName" placeholder="Add your comment ...">
                    </div>
                </div>
            @endif
        </form>
@endsection