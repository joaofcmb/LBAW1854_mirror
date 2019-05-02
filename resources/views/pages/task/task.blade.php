@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/project.css') }}" rel="stylesheet">
@endsection

@section('title', 'Task - View')

@section('body')
    <div class="navbar-dark sticky-top">
        @include('partials.main-navbar', [
            'active' => '',
            'auth' => 'session'
        ])

        @include('partials.sub-navbar', [
            'active' => '',
            'project' => $project,
            'isProjectManager' => $isProjectManager
        ])
    </div>

    @include('partials.backButton')

    <div class="row w-100 mx-auto">
        <div class="col-lg-8 px-0">
            <div id="content" class="container py-3 mb-4" style="background-color: var(--bg-light);">
                <div class="main-tab card open border-left-0 border-right-0 border-bottom-0 rounded-0 p-2 mb-2">
                    <h3>{{ $task->title }}</h3>
                    <p class="mx-2 mb-2">{{ $task->description }}</p>
                    <div class="work-progress mx-2 mb-1">
                        <h6 class="text-center mb-1"><i class="fas fa-chart-line mr-1"></i>{{ $task->progress }}% done</h6>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped bg-success  progress-bar-animated" role="progressbar" style="width:{{ $task->progress }}%" aria-valuenow="{{ $task->progress }}"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="time-progress mx-2 my-1">
                        <h6 class="text-center mb-1"><i class="far fa-clock mr-1"></i>{{ $task->timeLeft }} days left</h6>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped bg-warning  progress-bar-animated" role="progressbar" style="width:{{ (integer)$task->timePercentage }}%" aria-valuenow="{{ (integer)$task->timePercentage }}"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>

                @if($isProjectManager)
                    <div id="progress-form" class="collapse">
                        <div class="d-flex">
                            <label for="progress-range">{{ $task->progress }}</label>
                            <input type="range" value="{{ $task->progress }}" class="custom-range ml-1 mr-4" id="progress-range">
                        </div>
                    </div>
                    <div id="create" class="container-fluid mx-auto py-2">
                        <div class="row justify-content-around">
                            <a data-toggle="collapse" href="#progress-form">
                                <div class="col-sm- py-1 px-2">
                                    Update Progress <i class="far fa-edit"></i>
                                </div>
                            </a>
                            <a href="{{ route('task-edit', ['id_project' => $project->id, 'id_task' => $task->id]) }}">
                                <div class="col-sm- py-1 px-2">
                                    Edit Task <i class="far fa-edit"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                @endif
                <div class="main-tab card open border-top-0 border-left-0 border-right-0 border-bottom-1 rounded-0 p-2 mt-5 mb-3">
                    <h3>Teams</h3>
                    <div class="mx-auto">
                        @foreach($teams as $team)
                            @include('partials.cards.team', ['team' => $team, 'projectTask' => ''])                           
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 px-0 mb-4">
            <div class="container pb-3 pt-lg-3 mb-4" id="discussion">
                <div class="main-tab card open border-left-0 border-right-0 border-bottom-1 rounded-0 p-2">
                    <h3>Discussion</h3>
                    @foreach($comments as $comment)
                        @include('partials.cards.comment', ['comment => $comment', 'taskComment' => true])
                    @endforeach
                    <form>
                        @if($canAddComment)
                            <div class="form-group m-2 mt-3">
                                <input type="text" class="form-control" id="projectName" placeholder="Add comment ...">
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection