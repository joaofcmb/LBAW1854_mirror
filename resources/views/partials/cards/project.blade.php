<div id="project" class="card py-2 px-3 mt-4 mx-3 mx-sm-5" style="border-top-width: 0.25em; border-top-color: {{ $project->color }};">
    <div class="d-flex justify-content-between">
        <a href="{{ route('project-overview', ['id_project' => $project->id]) }}">
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
            <a href="{{ route('profile', ['id' => $project->id_manager]) }}">
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
                <p class="m-0"><i class="fas fa-fw fa-check text-success mr-1"></i>{{ $project->num_tasks_done }} Tasks
                    concluded</p>
                <p class="m-0"><i class="fas fa-fw fa-times text-danger mr-1"></i>{{ $project->num_tasks_todo }} Tasks
                    remaining</p>
            </h6>
        </div>
    </div>
</div>