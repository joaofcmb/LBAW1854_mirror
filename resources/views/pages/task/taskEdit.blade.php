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
            <div id="menu-option" class="container-fluid justify-content-start mx-auto py-4">
                <a href="{{ route('project-tasks', ['id_project' => $project->id]) }}"><i class="fas fa-chevron-circle-left mx-2"></i>Back</a>
            </div>
            
            <div id="content" class="container py-3 mb-4" style="background-color: var(--bg-light);">
                <div class="main-tab card open border-left-0 border-right-0 border-bottom-0 rounded-0 p-2 mb-2">
                    <form id="edit-task-form" method="post" action="{{ route('task-edit-action', ['id_project' => $project->id , 'id_task' => $task->id]) }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="text" class="form-control form-control-lg" name="name" value="{{ $task->title }}">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="description" rows="4">{{ $task->description }}</textarea>
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
                        <h6 class="text-center mb-1"><i class="far fa-clock mr-1"></i>{{ $task->timeLeft }} days left</h6>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped bg-warning  progress-bar-animated"
                                 role="progressbar" style="width:{{ (integer)$task->timePercentage }}%" aria-valuenow="{{ (integer)$task->timePercentage }}" 
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>

                <div id="create" class="container-fluid mx-auto py-2">
                    <div class="row justify-content-center">
                        <a data-toggle="modal" data-target="#updateProgressModal">                            
                            <div class="col-sm- py-1 px-2">
                                Update Progress <i class="far fa-edit"></i>
                            </div>
                        </a>
                    </div>
                </div>
                <div id="create" class="container-fluid mx-auto my-3">
                    <div class="row justify-content-center">
                        <button class="col-sm- py-1 px-2" form="edit-task-form" type="submit">
                            Save 
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @include('partials.taskAssignment', [
            'teams' => $teams,
            'currentMilestone' => $currentMilestone,
            'milestones' => $milestones,
            'task' => $task
        ])

        <!-- Modal Update progress -->
        <div class="modal fade" id="updateProgressModal" tabindex="-1" role="dialog" aria-labelledby="updateProgressModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateProgressModalTitle">Update Progress</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <input id="progressValue" type="number" name="input" value="{{ $task->progress }}" oninput="this.form.range.value=this.value"/>

                            <input type="range" name="range" value="{{ $task->progress }}" class="custom-range mt-3" 
                                oninput="this.form.input.value=this.value"/>
                        </form>
                    </div>
                    <div id="brand-btn" class="modal-footer">
                        <button id="updateProgress-{{ $project->id }}-{{ $task->id }}" type="button" 
                            class="update-progress btn btn-primary" data-dismiss="modal">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection