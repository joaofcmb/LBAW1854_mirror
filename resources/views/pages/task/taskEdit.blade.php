@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/project.css') }}" rel="stylesheet">
@endsection

@section('title', 'Task - Edit')

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

    <div class="row w-100 mx-auto">
        <div class="col-lg-8 px-0">
            <div id="menu-option" class="container-fluid mx-auto py-4">
                <div class="row justify-content-start">
                    <div class="col-sm-4 ml-2">
                        <a href="{{ route('project-overview', ['id' => $project->id]) }}"><i class="fas fa-chevron-circle-left mx-2"></i>Back</a>
                    </div>
                </div>
            </div>
            <div id="content" class="container py-3 mb-4" style="background-color: var(--bg-light);">
                <div class="main-tab card open border-left-0 border-right-0 border-bottom-0 rounded-0 p-2 mb-2">

                    <form>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-lg" value="{{ $task->title }}">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control"
                                      rows="4">{{ $task->description }}</textarea>
                        </div>
                    </form>
                    <div class="work-progress mx-2 mb-1">
                        <h6 class="text-center mb-1"><i class="fas fa-chart-line mr-1"></i>{{ $task->progress }}% done</h6>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped bg-success  progress-bar-animated"
                                 role="progressbar" style="width:{{ $task->progress }}%" aria-valuenow="{{ $task->progress }}" aria-valuemin="0"
                                 aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="time-progress mx-2 my-1">
                        <h6 class="text-center mb-1"><i class="far fa-clock mr-1"></i>{{ (integer)$task->timeLeft }} days left</h6>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped bg-warning  progress-bar-animated"
                                 role="progressbar" style="width:{{ (integer)$task->timePercentage }}%" aria-valuenow="{{ (integer)$task->timePercentage }}" aria-valuemin="0"
                                 aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>

                <div id="progress-form" class="collapse">
                    <div class="d-flex">
                        <label for="progress-range">{{ $task->progress }}</label>
                        <input type="range" value="{{ $task->progress }}" class="custom-range ml-1 mr-4" id="progress-range">
                    </div>
                </div>
                <div id="create" class="container-fluid mx-auto py-3">
                    <div class="row justify-content-center">
                        <a data-toggle="collapse" href="#progress-form">
                            <div class="col-sm- py-1 px-2">
                                Update Progress <i class="far fa-edit"></i>
                            </div>
                        </a>
                    </div>
                </div>
                <div id="create" class="container-fluid mx-auto my-3">
                    <div class="row justify-content-center">
                        <a href="#">
                            <div class="col-sm- py-1 px-2">
                                Save
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div id="side-forum" class="col-lg-4 px-0 mt-md-5 mt-lg-3 mb-4">
            <div class="container h-100">
                <h3>TASK ASSIGNMENT</h3>

                <nav class="nav nav-tabs nav-justified">
                    <a class="nav-item nav-link active" data-toggle="tab" role="tab" href="#teams" aria-controls="teams"
                       aria-selected="true">Teams</a>
                    <a class="nav-item nav-link" data-toggle="tab" role="tab" href="#milestone"
                       aria-controls="milestone" aria-selected="false">Milestone</a>
                </nav>
                <div class="tab-content border border-top-0">
                    <div class="tab-pane fade show active" id="teams" role="tabpanel">
                        <div id="search" class="p-3">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Teams ..." aria-label="Teams ...">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <a> <i class="fa fa-search" aria-hidden="true"></i> Search</a>
                                    </button>
                                </div>
                            </div>
                        </div>

                        @foreach($teams as $team)
                            <div class="card open flex-row justify-content-between p-2 mx-3 my-2">
                                <div class="custom-control custom-checkbox">
                                    <input checked type="checkbox" class="custom-control-input" id="{{ $team->id }}">
                                    <label class="custom-control-label team-name" for="team1">{{ $team->name }}</label>
                                </div>
                                {{ $team->skill == null ? '' : $team->skill }}
                            </div>
                        @endforeach
                    </div>

                    <div class="tab-pane fade" id="milestone" role="tabpanel">
                        <div id="search" class="p-3">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Milestone ..."
                                       aria-label="Milestone ...">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <a> <i class="fa fa-search" aria-hidden="true"></i> Search</a>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card open flex-row justify-content-between p-2 mx-3 my-2">
                            <div class="custom-control custom-radio">
                                <input checked type="radio" class="custom-control-input" id="{{ $currentMilestone->id }}">
                                <label class="custom-control-label team-name" for="{{ $currentMilestone->id }}">{{ $currentMilestone->name }}</label>
                            </div>
                        </div>

                        @foreach($milestones as $milestone)
                            @if($milestone->id != $currentMilestone->id)
                                <div class="card flex-row justify-content-between p-2 mx-3 my-2">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="{{ $milestone->id }}">
                                        <label class="custom-control-label team-name" for="{{ $milestone->id }}">{{ $milestone->name }}</label>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="text-center">
                    <div id="create" class="container-fluid mx-auto mb-2">
                        <div class="row mt-4 justify-content-center">
                            <a href="#">
                                <div class="col-sm- px-3">Assign</div>
                            </a>
                        </div>
                    </div>
                    <h6 class="py-1">{{ $task->title }}</h6>
                </div>
            </div>
        </div>
    </div>


    <footer class="fixed-bottom p-1 pl-2">
        COPYRIGHT © EPMA 2019
    </footer>

@endsection