<div @if(!$ungrouped) id="group-{{ $project->id }}-{{ $taskGroup->id }}" @endif
    class="main-tab task-group border-hover flex-shrink-0 card open border-left-0 border-right-0 rounded-0 py-2 mr-5">
    @if($isProjectManager)
        <div id="task-group-hover" class="mx-auto mb-1">
            @if(!$ungrouped)
                <a href="{{ route('task-create', ['id_project' => $project->id, 'id_taskgroup' => $taskGroup->id]) }}"><i class="fas fa-plus fa-fw hover-icon mr-2"></i></a>
                <button id="editTaskGroup-{{ $taskGroup->id }}" type="button" data-toggle="modal" data-target="#editTaskGroupModal" 
                    data-whatever="{{ $title }}" class="editTaskGroupButton mx-2 px-0"><i class="far fa-edit fa-fw hover-icon"></i></button>
                <i id="removeTaskGroup-{{ $project->id }}-{{ $taskGroup->id }}" class="remove-task-group far fa-trash-alt fa-fw hover-icon ml-2"></i>
            @else
                <a href="{{ route('task-create', ['id_project' => $project->id]) }}"><i class="fas fa-plus fa-fw hover-icon mr-2"></i></a>
            @endif
        </div>
    @endif
    <div class="d-flex flex-shrink-0 text-center my-1 mx-auto">
        <h3>{{ $title }}</h3>
    </div>
    <div class="px-3 overflow-auto pr-2 pl-2 mt-1 mb-2">
        @foreach($tasks as $task)
            @include('partials.cards.task', [
                'task' => $task,
                'isProjectManager' => $isProjectManager,
                'isTasksPage' => true
            ])
        @endforeach
    </div>
</div>