@isset($isProjectManager)
    @isset($isTasksPage)
        <section id="task-{{$task->id}}"draggable="true" class="task draggable card border-hover p-2 m-2 mt-3">
    @else
        <section class="task card border-hover @isset($selected) sticky @endisset float-sm-left p-2 m-2 mt-3">
    @endisset
    
    @if($isProjectManager)
    <div class="mx-auto mb-1">
        <a href="{{ route('task-edit', ['id_project' => $project->id, 'id_task' => $task->id]) }}"><i class="far fa-edit hover-icon mr-2"></i></a>
        <a href="{{ route('task-assign', ['id_project' => $project->id, 'id_task' => $task->id]) }}"><i class="fas fa-link fa-fw hover-icon mx-2"></i></a>
        <a href=""><i class="far fa-trash-alt fa-fw hover-icon ml-2"></i></a>
    </div>
    @endif
@else
<section class="task card float-sm-left p-2 m-2 mt-3">
    <h5 class="card-title text-center pb-2 mb-2" style="border-color: {{$task->color}}">{{$task->project_name}}</h5>
@endisset  
    <h6 class="text-center mb-auto"><a href="{{ route('task', ['id_project' => $task->id_project, 'id_task' => $task->id]) }}">{{$task->title}}</a></h6>

    <p class="ml-1 m-0">{{ sizeof($task->teams) }} Teams</p>
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
        <h6 class="text-center mb-1"><i class="far fa-clock mr-1"></i>{{ $task->timeLeft }} days left</h6>
        <div class="progress">
            <div class="progress-bar progress-bar-striped bg-{{$task->timePercentage == 100 ? 'danger' : 'warning'  }} progress-bar-animated"
                 role="progressbar" style="width:{{ $task->timePercentage }}%" aria-valuenow="{{ $task->timePercentage }}" aria-valuemin="0" 
                 aria-valuemax="100">
            </div>
        </div>
    </div>
</section>