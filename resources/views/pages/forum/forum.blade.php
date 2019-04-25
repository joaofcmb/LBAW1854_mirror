@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/company-forum.css') }}" rel="stylesheet">
@endsection

@section('title', 'Company Forum')

@section('body')
<body class="bg-light">
    @include('partials.main-navbar', [
        'active' => 'forum',
        'isProjectForum' => $isProjectForum,
        'auth' => 'session'
     ])
    @if($isProjectForum)
        @include('partials.sub-navbar', [
            'active' => 'forum',
            'project' => $project,
        ])
    @endif

    <div class="container">
        <div id="forum-header">
            <div class="d-flex justify-content-between align-items-center my-2 py-3">
                <h3 class="m-0">{{ $isProjectForum ? 'PROJECT FORUM' : 'COMPANY FORUM' }}</h3>
                <a href="create-thread.html"><i class="fas fa-plus-circle"></i></a>
            </div>
        </div>
        <div id="forum" class="px-3 mb-5">
            @foreach($threads as $thread)
                <a href="{{ $isProjectForum ? route('forum-thread',['id_project' => $project->id, 'id_thread' => $thread->id]) : route('companyforum-thread', ['id_thread' => $thread->id]) }}">
                    <div class="card border-hover sticky p-2 my-4">
                        <div class="d-flex justify-content-between align-items-top">
                            <h5>{{ $thread->title }}</h5>
                                @if(Auth::user()->isAdmin() ||  Auth::user()->getAuthIdentifier() === $thread->id_author )
                                    <i class="fas fa-trash-alt mx-3"></i>
                                @endif
                        </div>
                        <div class="row">
                            <div class="col">
                                <i class="far fa-user mr-1"></i>
                                {{ $thread->author_name }}
                            </div>
                            <!-- <div class="notification mx-3"><i class="far fa-envelope mx-2"></i>2</div> -->
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    <footer class="fixed-bottom p-1 pl-2">
        COPYRIGHT © EPMA 2019
    </footer>
</body>
@endsection