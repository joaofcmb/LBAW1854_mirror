@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endsection

@section('title')
    <title>EPMA- Home</title>
@endsection

@section('body')
    <body class="bg-light">
    <nav class="navbar sticky-top navbar-expand-sm navbar-dark py-0 px-3 px-sm-1 px-lg-3">
        <a class="navbar-brand" href="">
            <img src="{{ asset('img/logo.png') }}" width="35" class="d-inline-block" alt="Website Logo">
            <span class="font-weight-bold font-italic">EPMA</span>
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse py-2" id="navbarSupportedContent">
            <a class="nav-item nav-link active mx-lg-3" href="{{ route('home') }}">HOME</a>
            <a class="nav-item nav-link mx-lg-3" href="search.html">SEARCH</a>
            <a class="nav-item nav-link mx-lg-3" href="{{ route('companyforum') }}">FORUM</a>
            <a class="nav-item nav-link mx-lg-3" href="profile.html">PROFILE</a>


            <div id="authentication" class="ml-auto">
                <a href="admin-users.html">
                    <img id="admin" class="profile-img d-inline-block rounded-circle my-auto" src="{{ asset('img/admin.png') }}"
                         width="50" height="50" alt="Website Logo">
                </a>
                <a href="{{ route('logout') }}">
                    <span class="font-weight-bold pl-3">Sign out</span>
                </a>
                <a href="" class="pl-lg-3"><img class="profile-img d-none d-md-inline-block rounded-circle my-auto" src="{{ asset('img/avatar.png') }}" width="50" height="50" alt="Profile Image"></a>
            </div>
        </div>
    </nav>

    <div class="row w-100 mx-auto">
        <div class="col-lg-8 px-0">
            <div id="content" class="container py-3 mb-4">
                <div class="main-tab card open border-left-0 border-right-0 border-bottom-0 rounded-0 p-2">
                    <button class="p-0" data-toggle="collapse" data-target="#ProjectManagement">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3>Project Management</h3>
                            <div class="collapse-element d-flex justify-content-end align-items-center">
                                <span class="font-weight-light mr-2">{{ sizeof($managementProjects) }} Project</span>
                                <a data-toggle="collapse" href="#ProjectManagement" aria-expanded="true"><i class="fas fa-angle-down mt-1"></i></a>
                                <a data-toggle="collapse" href="#ProjectManagement"><i class="fas fa-angle-up mt-1"></i></a>
                            </div>
                        </div>
                    </button>
                    <div class="collapse show mb-2" id="ProjectManagement">
                        @foreach ($managementProjects as $project)
                            <div id="project" class="card py-2 px-3 mt-4 mx-3 mx-sm-5" style="border-top-width: 0.25em;
                            border-top-color: {{ $project->color }};">
                                <div class="d-flex justify-content-between">
                                    <a href="./project.html">
                                        <h5 class="card-title mb-3 ">{{ $project->name }}</h5>
                                    </a>
                                    <h5>
                                        <a href=""><i class="{{ $project->favorite ? 'fas' : 'far' }} fa-star" aria-hidden="true"></i></a>
                                        <a href=""><i class="fa fa-{{ $project->lock ? 'unlock' : 'lock' }}" aria-hidden="true"></i></a>
                                    </h5>
                                </div>
                                <div class="row">
                                    <div class="col-sm-7">
                                        Project Manager:
                                        <a href="profile-visitor.html">
                                            <h6 class="d-inline-block mb-3">{{ $project->manager }}</h6>
                                        </a>
                                        <br>
                                        Brief Description:
                                        <h6 class="d-inline">{{ $project->description }}</h6>
                                    </div>
                                    <div class="col-sm-5 mt-3 mt-sm-0">
                                        Statistics
                                        <h6>
                                            <p class="m-0"><i class="far fa-fw fa-user mr-1"></i>{{ $project->teams }} Teams involved</p>
                                            <p class="m-0"><i class="fas fa-fw fa-check text-success mr-1"></i>{{ $project->tasks_done }} Tasks
                                                concluded</p>
                                            <p class="m-0"><i class="fas fa-fw fa-times text-danger mr-1"></i>{{ $project->tasks_todo }} Tasks
                                                remaining</p>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="main-tab card border-left-0 border-right-0 border-bottom-0 rounded-0 p-2">
                    <button class="p-0" data-toggle="collapse" data-target="#TeamProjects">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3>Team Projects</h3>
                            <div class="collapse-element d-flex justify-content-end align-items-center">
                                <span class="font-weight-light mr-2">{{ sizeof($teamProjects) }} Projects</span>
                                <a data-toggle="collapse" href="#TeamProjects"><i class="fas fa-angle-down mt-1"></i></a>
                                <a class="collapsed" data-toggle="collapse" href="#TeamProjects"><i class="fas fa-angle-up mt-1"></i></a>
                            </div>
                        </div>
                    </button>
                    <div class="collapse mb-2" id="TeamProjects">
                        @foreach($teamProjects as $project)
                            <div id="project" class="card py-2 px-3 mt-4 mx-3 mx-sm-5" style="border-top-width: 0.25em;
                        border-top-color: {{ $project->color }};">
                                <div class="d-flex justify-content-between">
                                    <a href="./project.html">
                                        <h5 class="card-title mb-3 ">{{ $project->name }}</h5>
                                    </a>
                                    <h5>
                                        <a href=""><i class="{{ $project->favorite ? 'fas' : 'far' }} fa-star" aria-hidden="true"></i></a>
                                        <a href=""><i class="fa fa-{{ $project->lock ? 'unlock' : 'lock' }}" aria-hidden="true"></i></a>
                                    </h5>
                                </div>
                                <div class="row">
                                    <div class="col-sm-7">
                                        Project Manager:
                                        <a href="profile-visitor.html">
                                            <h6 class="d-inline-block mb-3">{{ $project->manager }}</h6>
                                        </a>
                                        <br>
                                        Brief Description:
                                        <h6 class="d-inline">{{ $project->description }}</h6>
                                    </div>
                                    <div class="col-sm-5 mt-3 mt-sm-0">
                                        Statistics
                                        <h6>
                                            <p class="m-0"><i class="far fa-fw fa-user mr-1"></i>{{ $project->teams }} Teams involved</p>
                                            <p class="m-0"><i class="fas fa-fw fa-check text-success mr-1"></i>{{ $project->tasks_done }} Tasks
                                                concluded</p>
                                            <p class="m-0"><i class="fas fa-fw fa-times text-danger mr-1"></i>{{ $project->tasks_todo }} Tasks
                                                remaining</p>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="main-tab card border-left-0 border-right-0 rounded-0 p-2">
                    <button class="p-0" data-toggle="collapse" data-target="#TeamTasks">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3>Team Tasks</h3>
                            <div class="collapse-element d-flex justify-content-end align-items-center">
                                <span class="font-weight-light mr-2">{{ sizeof($teamTasks) }} Tasks</span>
                                <a data-toggle="collapse" href="#TeamTasks"><i class="fas fa-angle-down mt-1"></i></a>
                                <a class="collapsed" data-toggle="collapse" href="#TeamTasks"><i
                                            class="fas fa-angle-up mt-1"></i></a>
                            </div>
                        </div>
                    </button>
                    <div class="collapse mx-auto" id="TeamTasks">
                        @foreach($teamTasks as $task)
                            <section class="task card float-sm-left p-2 m-2 mt-3">
                                <h5 class="card-title text-center pb-2 mb-2" style="border-color: {{ $task->color }}">{{ $task->project_name }}</h5>
                                <h6 class="text-center mb-auto"><a href="task.html">{{ $task->title }}</a></h6>

                                <p class="ml-1 m-0">{{ $task->teams }} Teams</p>
                                <p class="ml-1 mb-2">{{ $task->developers }} Developers</p>

                                <div class="work-progress mx-2 mb-1">
                                    <h6 class="text-center mb-1"><i class="fas fa-chart-line mr-1"></i>{{ $task->progress }}% done</h6>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped bg-success progress-bar-animated"
                                             role="progressbar" style="width:{{ $task->progress }}%" aria-valuenow="{{ $task->progress }}" aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="time-progress mx-2 my-1">
                                    @php
                                        $currentDate = new DateTime(date("Y/m/d"));
                                        $creationDate = new DateTime($task['creation_date']);
                                        $deadline = new DateTime($task['deadline']);

                                        $interval_1 = $creationDate->diff($currentDate)->format('%R%a');
                                        $interval_2 = $creationDate->diff($deadline)->format('%R%a');

                                        $time_left = $creationDate->diff($deadline)->format('%R%a');
                                        $percentage = $interval_2 == 0 ? 100 : $interval_1/$interval_2 * 100;
                                    @endphp
                                    <h6 class="text-center mb-1"><i class="far fa-clock mr-1"></i>{{ (integer)$time_left }} days left</h6>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped bg-{{ $percentage == 100 ? 'danger' : 'warning'  }} progress-bar-animated"
                                             role="progressbar" style="width:{{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                             aria-valuemax="100"></div>
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
                    <a href="company-forum.html" id="companyForum">
                        <h3 class="m-0">COMPANY FORUM</h3>
                    </a>
                    <a href="create-thread.html"><i class="fas fa-plus-circle"></i></a>
                </div>
                @foreach($threads as $thread)
                    <a href="thread.html">
                        <section class="card sticky p-2 my-3">
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