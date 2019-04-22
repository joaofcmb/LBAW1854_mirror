@foreach($tabs as $tab)
    @if($loop -> first)
        <div class="main-tab card {{$tab->collapse}} border-left-0 border-right-0 rounded-0 p-2">
    @else
        <div class="main-tab card {{$tab->collapse}} border-left-0 border-right-0 border-top-0 rounded-0 p-2">
    @endif

    @isset($tab->collapse)
        <button class="p-0" data-toggle="collapse" data-target="#TeamTasks">
            <div class="d-flex justify-content-between align-items-center">
                <h3>Team Tasks</h3>
                <div class="collapse-element d-flex justify-content-end align-items-center">
                    <span class="font-weight-light mr-2">2 Tasks</span>
                    <a data-toggle="collapse" href="#TeamTasks"><i class="fas fa-angle-down mt-1"></i></a>
                    <a class="collapsed" data-toggle="collapse" href="#TeamTasks"><i class="fas fa-angle-up mt-1"></i></a>
                </div>
            </div>
        </button>
    @endisset
@endforeach

<div class="main-tab card open border-left-0 border-right-0 border-bottom-0 rounded-0 p-2">
    <button class="p-0" data-toggle="collapse" data-target="#ProjectManagement">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Project Management</h3>
            <div class="collapse-element d-flex justify-content-end align-items-center">
                <span class="font-weight-light mr-2">1 Project</span>
                <a data-toggle="collapse" href="#ProjectManagement" aria-expanded="true"><i class="fas fa-angle-down mt-1"></i></a>
                <a data-toggle="collapse" href="#ProjectManagement"><i class="fas fa-angle-up mt-1"></i></a>
            </div>
        </div>
    </button>
    <div class="collapse show mb-2" id="ProjectManagement">
        @foreach ($management as $project)
            <div id="project" class="card py-2 px-3 mt-4 mx-3 mx-sm-5" style="border-top-width: 0.25em;
                    border-top-color: {{ $project->color }};">
                <div class="d-flex justify-content-between">
                    <a href="./project.html">
                        <h5 class="card-title mb-3 ">{{ $project->name }}</h5>
                    </a>
                    <h5>
                        <a href=""><i class="{{ $project->favorite ? 'fas' : 'far' }} fa-star" aria-hidden="true"></i></a>
                        <a href=""><i class="fa fa-unlock" aria-hidden="true"></i></a>
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
                <span class="font-weight-light mr-2">2 Projects</span>
                <a data-toggle="collapse" href="#TeamProjects"><i class="fas fa-angle-down mt-1"></i></a>
                <a class="collapsed" data-toggle="collapse" href="#TeamProjects"><i class="fas fa-angle-up mt-1"></i></a>
            </div>
        </div>
    </button>
    <div class="collapse mb-2" id="TeamProjects">
        <div id="project" class="card py-2 px-3 mt-4 mx-3 mx-sm-5" style="border-top-width: 0.25em;
                        border-top-color: cornflowerblue;">
            <div class="d-flex justify-content-between">
                <a href="./project.html">
                    <h5 class="card-title mb-3 ">Company Website</h5>
                </a>
                <h5>
                    <a href=""><i class="fas fa-star" aria-hidden="true"></i></a>
                    <a href=""><i class="fa fa-unlock" aria-hidden="true"></i></a>
                </h5>
            </div>
            <div class="row">
                <div class="col-sm-7">
                    Project Manager:
                    <a href="profile-visitor.html">
                        <h6 class="d-inline-block mb-3"> John Doe</h6>
                    </a>
                    <br>
                    Brief Description:
                    <h6 class="d-inline"> Company website for exposing our products.</h6>
                </div>
                <div class="col-sm-5 mt-3 mt-sm-0">
                    Statistics
                    <h6>
                        <p class="m-0"><i class="far fa-fw fa-user mr-1"></i>10 Teams involved</p>
                        <p class="m-0"><i class="fas fa-fw fa-check text-success mr-1"></i>124 Tasks
                            concluded</p>
                        <p class="m-0"><i class="fas fa-fw fa-times text-danger mr-1"></i>32 Tasks
                            remaining</p>
                    </h6>
                </div>
            </div>
        </div>
        <div id="project" class="card py-2 px-3 mt-4 mx-3 mx-sm-5" style="border-top-width: 0.25em;
                            border-top-color: rgb(158, 32, 32);">
            <div class="d-flex justify-content-between">
                <a href="./project.html">
                    <h5 class="card-title mb-3 ">Company TimeTable</h5>
                </a>
                <h5>
                    <a href=""><i class="far fa-star" aria-hidden="true"></i></a>
                    <a href=""><i class="fa fa-unlock" aria-hidden="true"></i></a>
                </h5>
            </div>
            <div class="row">
                <div class="col-sm-7">
                    Project Manager:
                    <a href="#">
                        <h6 class="d-inline-block mb-3"> Jane Doe</h6>
                    </a>
                    <br>
                    Brief Description:
                    <h6 class="d-inline"> Company timetable for managing employee.</h6>
                </div>
                <div class="col-sm-5 mt-3 mt-sm-0">
                    Statistics
                    <h6>
                        <p class="m-0"><i class="far fa-fw fa-user mr-1"></i>10 Teams involved</p>
                        <p class="m-0"><i class="fas fa-fw fa-check text-success mr-1"></i>10 Tasks
                            concluded</p>
                        <p class="m-0"><i class="fas fa-fw fa-times text-danger mr-1"></i>45 Tasks
                            remaining</p>
                    </h6>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main-tab card border-left-0 border-right-0 rounded-0 p-2">
    <button class="p-0" data-toggle="collapse" data-target="#TeamTasks">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Team Tasks</h3>
            <div class="collapse-element d-flex justify-content-end align-items-center">
                <span class="font-weight-light mr-2">2 Tasks</span>
                <a data-toggle="collapse" href="#TeamTasks"><i class="fas fa-angle-down mt-1"></i></a>
                <a class="collapsed" data-toggle="collapse" href="#TeamTasks"><i
                            class="fas fa-angle-up mt-1"></i></a>
            </div>
        </div>
    </button>
    <div class="collapse mx-auto" id="TeamTasks">
        <section class="task card float-sm-left p-2 m-2 mt-3">
            <h5 class="card-title text-center pb-2 mb-2" style="border-color: cornflowerblue">Company
                Website</h5>
            <h6 class="text-center mb-auto"><a href="task.html">Add Responsive Design</a></h6>

            <p class="ml-1 m-0">1 Teams</p>
            <p class="ml-1 mb-2">9 Developers</p>

            <div class="work-progress mx-2 mb-1">
                <h6 class="text-center mb-1"><i class="fas fa-chart-line mr-1"></i>66% done</h6>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped bg-success progress-bar-animated"
                         role="progressbar" style="width:63%" aria-valuenow="63" aria-valuemin="0"
                         aria-valuemax="100"></div>
                </div>
            </div>
            <div class="time-progress mx-2 my-1">
                <h6 class="text-center mb-1"><i class="far fa-clock mr-1"></i>2 days left</h6>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped bg-warning progress-bar-animated"
                         role="progressbar" style="width:46%" aria-valuenow="46" aria-valuemin="0"
                         aria-valuemax="100"></div>
                </div>
            </div>
        </section>

        <section class="task card float-sm-left p-2 m-2 mt-3">
            <h5 class="card-title text-center pb-2 mb-2" style="border-color: red">Company TimeTable
            </h5>
            <h6 class="text-center mb-auto">User Interface</h6>

            <p class="ml-1 m-0">2 Teams</p>
            <p class="ml-1 mb-2">21 Developers</p>

            <div class="work-progress mx-2 mb-1">
                <h6 class="text-center mb-1"><i class="fas fa-chart-line mr-1"></i>15% done</h6>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped bg-success progress-bar-animated"
                         role="progressbar" style="width:15%" aria-valuenow="15" aria-valuemin="0"
                         aria-valuemax="100"></div>
                </div>
            </div>
            <div class="time-progress mx-2 my-1">
                <h6 class="text-center mb-1"><i class="far fa-clock mr-1"></i>13 days left</h6>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped bg-warning progress-bar-animated"
                         role="progressbar" style="width:28%" aria-valuenow="28" aria-valuemin="0"
                         aria-valuemax="100"></div>
                </div>
            </div>

        </section>
    </div>
</div>