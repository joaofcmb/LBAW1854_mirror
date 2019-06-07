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

    <div id="menu-option" class="container-fluid justify-content-start mx-auto py-4">
        <a href="{{ route('project-overview', ['id' => $project->id]) }}"><i class="fas fa-chevron-circle-left mx-2"></i>Back</a>
    </div>    

    <div class="row w-100 mx-auto">
        <div class="col-lg-8 px-0">
            <div id="content" class="container py-3 mb-4" style="background-color: var(--bg-light);">
                <div class="main-tab card open border-left-0 border-right-0 border-bottom-0 rounded-0 p-2 mb-2">
                    <h3>{{ $task->title }}</h3>
                    <p class="mx-2 mb-2">{{ $task->description }}</p>
                    <div class="work-progress mx-2 mb-1">
                        <h6 class="text-center mb-1"><i class="fas fa-chart-line mr-1"></i>{{ $task->progress }}% done</h6>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped bg-success  progress-bar-animated" role="progressbar" 
                                style="width:{{ $task->progress }}%" aria-valuenow="{{ $task->progress }}"
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

                @if($isProjectManager || $isTeamLeader)
                    <div id="create" class="container-fluid mx-auto py-2">
                        <div class="row justify-content-around">
                            <a data-toggle="modal" data-target="#updateProgressModal">                            
                                <div class="col-sm- py-1 px-2">
                                    Update Progress <i class="far fa-edit"></i>
                                </div>
                            </a>
                            @if($isProjectManager)
                                <a href="{{ route('task-edit', ['id_project' => $project->id, 'id_task' => $task->id]) }}">
                                    <div class="col-sm- py-1 px-2">
                                        Edit Task <i class="far fa-edit"></i>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Modal Update progress -->
                    <div class="modal fade" id="updateProgressModal" tabindex="-1" role="dialog" aria-labelledby="updateProgressModalTitle" 
                        aria-hidden="true">
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
                        @include('partials.cards.comment', [
                            'comment => $comment', 
                            'taskComment' => true, 
                            'task' => $task,
                            'project' => $project
                        ])
                    @endforeach
                    @if($canAddComment)
                        <form id="addTaskComment-{{ $task->id }}-{{ $project->id }}" class="add-task-comment">
                            <div class="form-group m-2 mt-3">
                                <input type="text" class="form-control" id="commentContent" placeholder="Add comment ..." required>
                            </div>                        
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection