@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/project.css') }}" rel="stylesheet">
@endsection

@section('title', 'Project - Overview')

@section('body')
    <body class="bg-light">
    <div class="navbar-dark sticky-top">
        @include('partials.main-navbar', [
                'active' => '',
                'owner' => true,
                'auth' => 'session'
        ])

        @include('partials.sub-navbar', [
            'active' => 'overview',
            'project' => $project,
        ])
    </div>

    <div class="row w-100 mx-auto">
        <div class="col-lg-8 px-0">
            <div id="content" class="container py-3 mb-4">
                <div class="roadmap m-sm-5 my-4">
                    <div class="roadmap-left"></div>
                    <div class="roadmap-diagram border-top-0 border-bottom-0 d-flex justify-content-between align-items-center">
                        <div class="p-1"></div>
                            @foreach($milestones as $milestone)
                                @if($milestone->deadline < $date)
                                    <div class="milestone py-2"><i class="far fa-dot-circle align-middle"></i></div>
                                @else
                                    <div class="milestone py-2"><i class="far fa-circle align-middle"></i></div>
                                @endif
                            @endforeach
                        <div class="p-1"></div>
                    </div>
                    <div class="roadmap-right"></div>
                    <div class="milestone-description mx-auto text-center">
                        {{ $currentMilestone->timeLeft }} days left
                        <h6>{{ $currentMilestone->name }}</h6>
                    </div>
                </div>

                <div class="main-tab card border-left-0 border-right-0 border-bottom-0 rounded-0 p-2">
                    <button class="p-0" data-toggle="collapse" data-target="#TodoTasks">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3>Todo Tasks</h3>
                            <div class="collapse-element d-flex justify-content-end align-items-center">
                                <span class="font-weight-light mr-2">{{ sizeof($project->tasks_todo) }} Task</span>
                                <a data-toggle="collapse" href="#TodoTasks"><i class="fas fa-angle-down mt-1"></i></a>
                                <a class="collapsed" data-toggle="collapse" href="#TodoTasks"><i
                                            class="fas fa-angle-up mt-1"></i></a>
                            </div>
                        </div>
                    </button>
                    <div class="collapse mx-auto" id="TodoTasks">
                        @foreach($project->tasks_todo as $task)
                            <section class="task card border-hover float-sm-left p-2 m-2 mt-3">
                                <div class="mx-auto mb-1">
                                    <i class="far fa-edit hover-icon mr-2"></i>
                                    <i class="fas fa-link fa-fw hover-icon mx-2"></i>
                                    <i class="far fa-trash-alt fa-fw hover-icon ml-2"></i>
                                </div>
                                <h6 class="text-center mb-auto">{{$task->title}}</h6>

                                <p class="ml-1 m-0">{{$task->teams}} Teams</p>
                                <p class="ml-1 mb-2">{{$task-> developers}} Developers</p>

                                <div class="work-progress mx-2 mb-1">
                                    <h6 class="text-center mb-1"><i class="fas fa-chart-line mr-1"></i>{{ $task->progress }}% done</h6>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped bg-success progress-bar-animated"
                                             role="progressbar" style="width:{{ $task->progress }}%" aria-valuenow="{{ $task->progress }}" aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="time-progress mx-2 my-1">
                                    <h6 class="text-center mb-1"><i class="far fa-clock mr-1"></i>{{$task->timeLeft }} days left</h6>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped bg-{{$task->timePercentage == 100 ? 'danger' : 'warning'  }} progress-bar-animated"
                                             role="progressbar" style="width:{{ $task->timePercentage }}%" aria-valuenow="{{ $task->timePercentage }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endforeach
                    </div>
                </div>
                <div class="main-tab card border-left-0 border-right-0 border-bottom-0 rounded-0 p-2">
                    <button class="p-0" data-toggle="collapse" data-target="#OngoingTasks">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3>Ongoing Tasks</h3>
                            <div class="collapse-element d-flex justify-content-end align-items-center">
                                <span class="font-weight-light mr-2">{{ sizeof($project->tasks_ongoing) }} Tasks</span>
                                <a data-toggle="collapse" href="#OngoingTasks" aria-expanded="true"><i
                                            class="fas fa-angle-down mt-1"></i></a>
                                <a data-toggle="collapse" href="#OngoingTasks"><i class="fas fa-angle-up mt-1"></i></a>
                            </div>
                        </div>
                    </button>
                    <div class="collapse show mx-auto" id="OngoingTasks">
                        @foreach($project->tasks_ongoing as $task)
                            <section class="task card border-hover float-sm-left p-2 m-2 mt-3">
                                <div class="mx-auto mb-1">
                                    <i class="far fa-edit hover-icon mr-2"></i>
                                    <i class="fas fa-link fa-fw hover-icon mx-2"></i>
                                    <i class="far fa-trash-alt fa-fw hover-icon ml-2"></i>
                                </div>
                                <h6 class="text-center mb-auto">{{$task->title}}</h6>

                                <p class="ml-1 m-0">{{$task->teams}} Teams</p>
                                <p class="ml-1 mb-2">{{$task-> developers}} Developers</p>

                                <div class="work-progress mx-2 mb-1">
                                    <h6 class="text-center mb-1"><i class="fas fa-chart-line mr-1"></i>{{ $task->progress }}% done</h6>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped bg-success progress-bar-animated"
                                             role="progressbar" style="width:{{ $task->progress }}%" aria-valuenow="{{ $task->progress }}" aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="time-progress mx-2 my-1">
                                    <h6 class="text-center mb-1"><i class="far fa-clock mr-1"></i>{{$task->timeLeft }} days left</h6>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped bg-{{$task->timePercentage == 100 ? 'danger' : 'warning'  }} progress-bar-animated"
                                             role="progressbar" style="width:{{ $task->timePercentage }}%" aria-valuenow="{{ $task->timePercentage }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endforeach
                    </div>
                </div>
                <div class="main-tab card border-left-0 border-right-0 rounded-0 p-2">
                    <button class="p-0" data-toggle="collapse" data-target="#DoneTasks">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3>Done Tasks</h3>
                            <div class="collapse-element d-flex justify-content-end align-items-center">
                                <span class="font-weight-light mr-2">{{ sizeof($project->tasks_done) }} Tasks</span>
                                <a data-toggle="collapse" href="#DoneTasks"><i class="fas fa-angle-down mt-1"></i></a>
                                <a class="collapsed" data-toggle="collapse" href="#DoneTasks"><i
                                            class="fas fa-angle-up mt-1"></i></a>
                            </div>
                        </div>
                    </button>
                    <div class="collapse mx-auto" id="DoneTasks">
                        @foreach($project->tasks_done as $task)
                            <section class="task card border-hover float-sm-left p-2 m-2 mt-3">
                                <div class="mx-auto mb-1">
                                    <i class="far fa-edit hover-icon mr-2"></i>
                                    <i class="fas fa-link fa-fw hover-icon mx-2"></i>
                                    <i class="far fa-trash-alt fa-fw hover-icon ml-2"></i>
                                </div>
                                <h6 class="text-center mb-auto">{{$task->title}}</h6>

                                <p class="ml-1 m-0">{{$task->teams}} Teams</p>
                                <p class="ml-1 mb-2">{{$task-> developers}} Developers</p>

                                <div class="work-progress mx-2 mb-1">
                                    <h6 class="text-center mb-1"><i class="fas fa-chart-line mr-1"></i>{{ $task->progress }}% done</h6>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped bg-success progress-bar-animated"
                                             role="progressbar" style="width:{{ $task->progress }}%" aria-valuenow="{{ $task->progress }}" aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="time-progress mx-2 my-1">
                                    <h6 class="text-center mb-1"><i class="far fa-clock mr-1"></i>{{$task->timeLeft }} days left</h6>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped bg-{{$task->timePercentage == 100 ? 'danger' : 'warning'  }} progress-bar-animated"
                                             role="progressbar" style="width:{{ $task->timePercentage }}%" aria-valuenow="{{ $task->timePercentage }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div id="side-forum" class="col-lg-4 px-0 mt-md-5 mt-lg-3 mb-4">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('project-forum', ['id' => $forum->id]) }}" style="text-decoration: none; color: black;">
                        <h3 class="m-0">PROJECT FORUM</h3>
                    </a>
                    <a href="create-thread-project.html"><i class="fas fa-plus-circle"></i></a>
                </div>
                @foreach($threads as $thread)
                    <a href="{{ route('forum-thread', ['id_project' => $project->id, 'id_thread' => $thread->id]) }}" style="color: black; text-decoration: none;">
                        <section class="card border-hover sticky p-2 my-3">
                            <div class="d-flex justify-content-between align-items-top">
                                <h5>{{ $thread->title }}</h5>
                                <!-- <div class="notification"><i class="far fa-envelope mx-2"></i>2</div> -->
                            </div>
                            {{ $thread->author_name }}
                        </section>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <footer class="fixed-bottom p-1 pl-2">
        COPYRIGHT © EPMA 2019
    </footer>
    </body>
@endsection