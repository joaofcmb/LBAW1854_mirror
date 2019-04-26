<div class="main-tab task-group border-hover flex-shrink-0 card open border-left-0 border-right-0 rounded-0 py-2 mr-5">
    @if($isProjectManager)
        <div class="mx-auto mb-1">
            @if($ungrouped)
                <a href=""><i class="fas fa-plus fa-fw hover-icon"></i></a>
            @else
                <a href=""><i class="fas fa-plus fa-fw hover-icon mr-2"></i></a>
                <a href=""><i class="far fa-edit fa-fw hover-icon mx-2"></i></a>
                <a href=""><i class="far fa-trash-alt fa-fw hover-icon ml-2"></i></a>
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