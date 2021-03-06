@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/create-task-thread.css') }}" rel="stylesheet">
@endsection

@section('title', $isProjectForum ? 'Project - Forum' : 'Company Forum')

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
            <a href="{{ route('project-forum', ['id' => $project->id]) }}"><i class="fas fa-chevron-circle-left mx-2"></i>Back</a>
        </div>
    @else
        <div id="menu-option" class="container-fluid justify-content-start mx-auto py-4">
            <a href="{{ route('companyforum') }}"><i class="fas fa-chevron-circle-left mx-2"></i>Back</a>
        </div>
    @endif

    <div id="search-content" class="container px-3">
        <div class="row">
            <div class="col-lg-12 px-sm-3 px-lg-5">
                <div class="card my-2">
                    <div class="card-header">
                        <h5>Create Thread</h5>
                    </div>
                    <div class="card-body">
                        <form id="create-thread-form" method="post" action="{{ $isProjectForum ? route('forum-create-thread-action', ['id_project' => $project->id]) : route('company-forum-create-thread-action') }}">
                            {{ csrf_field() }}
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <input type="text" class="form-control" id="projectName" name="title" placeholder="Title" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" id="projectDescription" name="description" placeholder="Description"
                                          rows="7" required></textarea>
                            </div>
                        </form>
                        <div id="action-button" class="text-center mb-2">
                            <button class="mt-3 px-0" form="create-thread-form" type="submit">
                                <a class="btn">Create</a>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection