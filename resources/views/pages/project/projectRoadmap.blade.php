@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/project.css') }}" rel="stylesheet">
@endsection

@section('title', 'Project - Roadmap')

@section('body')
    <body class="bg-light">
    <div class="navbar-dark sticky-top">
        @include('partials.main-navbar', [
                'active' => '',
                'owner' => true,
                'auth' => 'session'
        ])

        @include('partials.sub-navbar', [
            'active' => 'roadmap',
            'project' => $project,
        ])
    </div>

    <div class="row w-100 mx-auto">
        <div id="content" class="container py-3 mb-4">
            <div id="create" class="container-fluid mx-auto">
                <div class="row mt-4 justify-content-center">
                    <a href="">
                        <div class="col-sm- py-2 px-3">
                            <span>Create Milestone</span>
                            <i class="fas fa-plus-circle ml-2" style="border: none;"></i>
                        </div>
                    </a>
                </div>
            </div>

            <div class="roadmap my-5 mx-sm-5">
                <div class="roadmap-left"></div>
                <div class="roadmap-diagram border-top-0 border-bottom-0 d-flex justify-content-between align-items-center">
                    <div class="p-1"></div>
                    @foreach($milestones as $milestone)
                        <a data-toggle="collapse" href="#{{ $milestone->name }}" class="milestone py-2"><i class="far fa-{{ $milestone->deadline < $date ? 'dot-' : '' }}circle align-middle"></i></a>
                    @endforeach
                    <div class="p-1"></div>
                </div>
                <div class="roadmap-right"></div>
                <div class="d-none d-lg-flex justify-content-between align-items-center">
                    <div class="p-4"></div>
                    @foreach($milestones as $milestone)
                        <a data-toggle="collapse"
                           href="#{{ $milestone->name }}"
                            {{ $milestone->id == $currentMilestone->id ? 'aria-expanded=true' : '' }}
                           class=" {{ $milestone->id == $currentMilestone->id ? 'milestone-info active' : 'collapsed milestone-info'}} text-center pb-3"
                           style="border-color: rgb(12, 116, 214);">
                            <h6 class="mb-1">{{ date_format(date_create($milestone->deadline), 'Y-m-d') }}</h6>
                            {{ $milestone->deadline < $date ? 'Elapsed' : $milestone->timeLeft . 'days left' }}
                        </a>
                    @endforeach
                    <div class="p-4"></div>
                </div>
            </div>

            <div id="{{ $currentMilestone->name }}" data-parent="#content" class="collapse show main-tab card border-left-0 border-right-0 rounded-0 p-2">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>{{ $currentMilestone->name }}<i class="far fa-edit ml-2"></i></h3>
                    <span class="font-weight-light mr-2 flex-shrink-0">{{ sizeof($currentMilestoneTasks) }} remaining</span>
                </div>
                <div class="mx-auto">
                    @foreach($currentMilestoneTasks as $task)
                        <section class="task card border-hover float-sm-left p-2 m-2 mt-3">
                            <div class="mx-auto mb-1">
                                <a href=""><i class="far fa-edit hover-icon mr-2"></i></a>
                                <a href=""><i class="fas fa-link fa-fw hover-icon mx-2"></i></a>
                                <a href=""><i class="far fa-trash-alt fa-fw hover-icon ml-2"></i></a>
                            </div>

                            <h6 class="text-center mb-auto"><a href="">{{ $task->title }}</a></h6>

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

    <footer class="fixed-bottom p-1 pl-2">
        COPYRIGHT © EPMA 2019
    </footer>
    </body>
@endsection