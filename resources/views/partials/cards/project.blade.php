<div id="project1-{{ $project->id }}" class="project card py-2 px-3 mt-4 mx-3 mx-sm-5 mb-2" style="border-top-width: 0.25em; border-top-color: {{ $project->color }};">
    <div class="d-flex justify-content-between">
        <a href="{{ $project->isLocked ? '' : route('project-overview', ['id_project' => $project->id]) }}">
            <h5 class="card-title my-1">{{ $project->name }}</h5>
        </a>
        <h5 class="flex-grow-1 d-flex justify-content-end align-items-center">
            @isset($admin)
                <a href="{{ route('admin-edit-project', ['id' => $project->id]) }}"><i class="far fa-edit"></i> </a>
                <a id="removeProject-{{ $project->id }}" class="remove-project pl-2"> <i class="far fa-trash-alt"></i></a>
            @else
                <a><i id="project-{{ $project->id }}" class="favorite {{ $project->favorite ? 'fas' : 'far' }} fa-star" style="cursor: pointer;" aria-hidden="true" title="Click this button to {{ $project->favorite ? 'unfavorite' : 'favorite' }} your project"></i></a>
                <i class="pl-1 fa fa-{{ $project->isLocked ? 'lock' : 'unlock' }}" aria-hidden="true"></i>
            @endisset
        </h5>
    </div>
    <div class="row">
        <div class="col-sm-7">
            Project Manager:
            <a href="{{ route('profile', ['id' => $project->id_manager]) }}">
                <h6 class="d-inline-block mb-3">{{ $project->manager->first_name . " " . $project->manager->last_name }}</h6>
            </a>
            <br>
            Brief Description:
            <h6 class="d-inline">{{ $project->description }}</h6>
        </div>
        <div class="col-sm-5 mt-3 mt-sm-0">
            Statistics            
            <h6 class="mb-0"><i class="far fa-fw fa-user mr-1"></i>{{ $project->teams }} Teams involved</h6>
            <h6 class="mb-0"><i class="fas fa-fw fa-check text-success mr-1"></i>{{ sizeof($project->tasks_done) }} Tasks
                concluded</h6>
            <h6 class="mb-0"><i class="fas fa-fw fa-times text-danger mr-1"></i>{{ sizeof($project->tasks_ongoing) + sizeof($project->tasks_todo) }} Tasks
                remaining</h6>
        </div>
    </div>
</div>