@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/project.css') }}" rel="stylesheet">
@endsection

@section('title', 'Task - Assign')

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
            <div id="content" class="container py-3 mb-4">
                <div class="main-tab card open border-left-0 border-right-0 rounded-0 p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Task List</h3>
                        <div class="d-flex justify-content-end align-items-center">
                            <span class="font-weight-light mr-2">{{ sizeof($tasks) + 1 }} Tasks</span>
                        </div>
                    </div>
                    <div class="mx-auto">
                        @include('partials.cards.task', [
                                'task' => $selectedTask,
                                'isProjectManager' => $isProjectManager
                            ])
                        @foreach($tasks as $task)
                            @if($task->id != $selectedTask->id)
                                @include('partials.cards.task', [
                                    'task' => $task,
                                    'isProjectManager' => $isProjectManager
                                ])
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div id="side-forum" class="col-lg-4 px-0 mt-md-5 mt-lg-3 mb-4">
            <div class="container h-100">
                <h3>TASK ASSIGNMENT</h3>

                <nav class="nav nav-tabs nav-justified">
                    <a class="nav-item nav-link active" data-toggle="tab" role="tab" href="#teams" aria-controls="teams" aria-selected="true">Teams</a>
                    <a class="nav-item nav-link" data-toggle="tab" role="tab" href="#milestone" aria-controls="milestone" aria-selected="false">Milestone</a>
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

                        @foreach($selectedTask->teams as $team)
                            <div class="card open flex-row justify-content-between p-2 mx-3 my-2">
                                <div class="custom-control custom-checkbox">
                                    <input checked type="checkbox" class="custom-control-input" id="team1">
                                    <label class="custom-control-label team-name" for="team1">{{ $team->name }}</label>
                                </div>
                                {{ $team->skill == null ? '' : $team->skill }}
                            </div>
                        @endforeach
                    </div>
                    <div class="tab-pane fade" id="milestone" role="tabpanel">
                        <div id="search" class="p-3">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Milestone ..." aria-label="Milestone ...">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <a> <i class="fa fa-search" aria-hidden="true"></i> Search</a>
                                    </button>
                                </div>
                            </div>
                        </div>

                        @if($currentMilestone->id != null)
                            <div class="card flex-row justify-content-between p-2 mx-3 my-2">
                                <div class="custom-control custom-radio">
                                    <input checked type="radio" class="custom-control-input" id="{{ $currentMilestone->id }}">
                                    <label class="custom-control-label team-name" for="{{ $currentMilestone->id }}">{{ $currentMilestone->name }}</label>
                                </div>
                            </div>
                        @endif

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
                    <h6 class="py-1">{{ $selectedTask->title }}</h6>
                </div>
            </div>
        </div>
    </div>

    <footer class="fixed-bottom p-1 pl-2">
        COPYRIGHT © EPMA 2019
    </footer>

@endsection