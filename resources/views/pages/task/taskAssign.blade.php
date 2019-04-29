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

    <div class="row w-100 mx-auto">
        <div class="col-lg-8 px-0">
            <div id="content" class="container py-3 mb-4">
                <div class="main-tab card open border-left-0 border-right-0 rounded-0 p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Task List</h3>
                        <div class="d-flex justify-content-end align-items-center">
                            <span class="font-weight-light mr-2">6 Tasks</span>
                        </div>
                    </div>
                    <div class="mx-auto">
                        <section class="task card border-hover sticky float-sm-left p-2 m-2 mt-3">
                            <div class="mx-auto mb-1">
                                <i class="far fa-edit hover-icon mr-2"></i>
                                <i class="fas fa-link fa-fw hover-icon mx-2"></i>
                                <i class="far fa-trash-alt fa-fw hover-icon ml-2"></i>
                            </div>
                            <h6 class="text-center mb-auto">Add Responsive Design</h6>

                            <p class="ml-1 m-0">1 Teams</p>
                            <p class="ml-1 mb-2">9 Developers</p>

                            <div class="work-progress mx-2 mb-1">
                                <h6 class="text-center mb-1"><i class="fas fa-chart-line mr-1"></i>66% done</h6>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width:63%" aria-valuenow="63" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="time-progress mx-2 my-1">
                                <h6 class="text-center mb-1"><i class="far fa-clock mr-1"></i>2 days left</h6>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-warning progress-bar-animated" role="progressbar" style="width:46%" aria-valuenow="46" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </section>
                        <section class="task card border-hover float-sm-left p-2 m-2 mt-3">
                            <div class="mx-auto mb-1">
                                <i class="far fa-edit hover-icon mr-2"></i>
                                <i class="fas fa-link fa-fw hover-icon mx-2"></i>
                                <i class="far fa-trash-alt fa-fw hover-icon ml-2"></i>
                            </div>
                            <h6 class="text-center mb-auto">Setup deployment hardware</h6>

                            <p class="ml-1 m-0">2 Teams</p>
                            <p class="ml-1 mb-2">19 Developers</p>

                            <div class="work-progress mx-2 mb-1">
                                <h6 class="text-center mb-1"><i class="fas fa-chart-line mr-1"></i>No Progress</h6>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width:0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="time-progress mx-2 my-1">
                                <h6 class="text-center mb-1"><i class="far fa-clock mr-1"></i>13 days left</h6>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-warning progress-bar-animated" role="progressbar" style="width:9%" aria-valuenow="46" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </section>
                        <section class="task card border-hover float-sm-left p-2 m-2 mt-3">
                            <div class="mx-auto mb-1">
                                <i class="far fa-edit hover-icon mr-2"></i>
                                <i class="fas fa-link fa-fw hover-icon mx-2"></i>
                                <i class="far fa-trash-alt fa-fw hover-icon ml-2"></i>
                            </div>
                            <h6 class="text-center mb-auto">Improve Interactivity with navbars</h6>

                            <p class="ml-1 m-0">1 Team</p>
                            <p class="ml-1 mb-2">4 Developers</p>

                            <div class="work-progress mx-2 mb-1">
                                <h6 class="text-center mb-1"><i class="fas fa-chart-line mr-1"></i>47% done</h6>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width:47%" aria-valuenow="47" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="time-progress mx-2 my-1">
                                <h6 class="text-center mb-1"><i class="far fa-clock mr-1"></i>2 days left</h6>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-warning progress-bar-animated" role="progressbar" style="width:56%" aria-valuenow="56" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </section>
                        <section class="task card border-hover float-sm-left p-2 m-2 mt-3">
                            <div class="mx-auto mb-1">
                                <i class="far fa-edit hover-icon mr-2"></i>
                                <i class="fas fa-link fa-fw hover-icon mx-2"></i>
                                <i class="far fa-trash-alt fa-fw hover-icon ml-2"></i>
                            </div>
                            <h6 class="text-center mb-auto">Test database performance</h6>

                            <p class="ml-1 m-0">1 Team</p>
                            <p class="ml-1 mb-2">6 Developers</p>

                            <div class="work-progress mx-2 mb-1">
                                <h6 class="text-center mb-1"><i class="fas fa-chart-line mr-1"></i>18% done</h6>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width:18%" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="time-progress mx-2 my-1">
                                <h6 class="text-center mb-1"><i class="far fa-clock mr-1"></i>13 days left</h6>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-warning progress-bar-animated" role="progressbar" style="width:15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </section>
                        <section class="task card border-hover float-sm-left p-2 m-2 mt-3">
                            <div class="mx-auto mb-1">
                                <i class="far fa-edit hover-icon mr-2"></i>
                                <i class="fas fa-link fa-fw hover-icon mx-2"></i>
                                <i class="far fa-trash-alt fa-fw hover-icon ml-2"></i>
                            </div>
                            <h6 class="text-center mb-auto">Import project tasks to EPMA</h6>

                            <p class="ml-1 m-0">1 Team</p>
                            <p class="ml-1 mb-2">2 Developers</p>

                            <div class="work-progress mx-2 mb-1">
                                <h6 class="text-center mb-1"><i class="fas fa-chart-line mr-1"></i>Done</h6>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width:100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="time-progress mx-2 my-1">
                                <h6 class="text-center mb-1"><i class="far fa-clock mr-1"></i>Deadline Elapsed</h6>
                                <div class="progress"></div>
                            </div>
                        </section>
                        <section class="task card border-hover float-sm-left p-2 m-2 mt-3">
                            <div class="mx-auto mb-1">
                                <i class="far fa-edit hover-icon mr-2"></i>
                                <i class="fas fa-link fa-fw hover-icon mx-2"></i>
                                <i class="far fa-trash-alt fa-fw hover-icon ml-2"></i>
                            </div>
                            <h6 class="text-center mb-auto">Integration of components</h6>

                            <p class="ml-1 m-0">3 Teams</p>
                            <p class="ml-1 mb-2">25 Developers</p>

                            <div class="work-progress mx-2 mb-1">
                                <h6 class="text-center mb-1"><i class="fas fa-chart-line mr-1"></i>Done</h6>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" style="width:100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="time-progress mx-2 my-1">
                                <h6 class="text-center mb-1"><i class="far fa-clock mr-1"></i>Deadline Elapsed</h6>
                                <div class="progress"></div>
                            </div>
                        </section>
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

                        <div class="card open flex-row justify-content-between p-2 mx-3 my-2">
                            <div class="custom-control custom-checkbox">
                                <input checked type="checkbox" class="custom-control-input" id="team1">
                                <label class="custom-control-label team-name" for="team1">Alpha</label>
                            </div>
                            Design
                        </div>
                        <div class="card open flex-row justify-content-between p-2 mx-3 my-2">
                            <div class="custom-control custom-checkbox">
                                <input checked type="checkbox" class="custom-control-input" id="team2">
                                <label class="custom-control-label team-name" for="team2">Beta</label>
                            </div>
                            Responsive
                        </div>
                        <div class="card flex-row justify-content-between p-2 mx-3 my-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="team3">
                                <label class="custom-control-label team-name" for="team3">Charlie</label>
                            </div>
                            Quality Assurance
                        </div>
                        <div class="card flex-row justify-content-between p-2 mx-3 my-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="team4">
                                <label class="custom-control-label team-name" for="team4">Delta</label>
                            </div>
                            Backend
                        </div>
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

                        <div class="card flex-row justify-content-between p-2 mx-3 my-2">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="team1">
                                <label class="custom-control-label team-name" for="team1">Delivery Preperation</label>
                            </div>
                        </div>
                        <div class="card open flex-row justify-content-between p-2 mx-3 my-2">
                            <div class="custom-control custom-radio">
                                <input checked type="radio" class="custom-control-input" id="team2">
                                <label class="custom-control-label team-name" for="team2">Frontend Polish</label>
                            </div>
                        </div>
                        <div class="card flex-row justify-content-between p-2 mx-3 my-2">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="team3">
                                <label class="custom-control-label team-name" for="team3">Project Setup</label>
                            </div>
                        </div>
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
                    <h6 class="py-1">Add Responsive Design</h6>
                </div>
            </div>
        </div>
    </div>

    <footer class="fixed-bottom p-1 pl-2">
        COPYRIGHT © EPMA 2019
    </footer>

@endsection