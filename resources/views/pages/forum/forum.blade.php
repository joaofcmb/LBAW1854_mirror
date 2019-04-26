@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/company-forum.css') }}" rel="stylesheet">
@endsection

@section('title', $isProjectForum ? 'Project - Forum' : 'Company Forum')

@section('body')
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
    @endif

    <div class="container">
        <div id="forum-header">
            <div class="d-flex justify-content-between align-items-center my-2 py-3">
                <h3 class="m-0">{{ $isProjectForum ? 'PROJECT FORUM' : 'COMPANY FORUM' }}</h3>
                <a href="{{ $isProjectForum ? route('forum-create-thread', ['id' => $project->id]) : route('company-forum-create-thread') }}">
                    @if($isProjectForum)
                        @if($canCreateThread)
                            <i class="fas fa-plus-circle"></i>
                        @endif
                    @else
                        <i class="fas fa-plus-circle"></i>
                    @endif
                </a>
            </div>
        </div>
        <div id="forum" class="px-3 mb-5">
            @foreach($threads as $thread)
                @include('partials.cards.thread', ['thread' => $thread, 'isProjectForum' => $isProjectForum])
            @endforeach
        </div>
    </div>
@endsection