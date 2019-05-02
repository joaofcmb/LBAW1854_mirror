<div id="menu-option" class="container-fluid justify-content-start mx-auto py-4">
    <a href="
    @switch($route)
        @case('admin-projects')
            {{ route('admin-projects') }}
            @break
        @case('admin-teams')
            {{ route('admin-teams') }}
            @break
        @case('project-forum')
            {{ route('project-forum', ['id' => $id_project]) }}
            @break
        @case('company-forum')
            {{ route('companyforum') }}
            @break
        @case('overview')
            {{ route('project-overview', ['id' => $id_project]) }}
            @break
        @case('tasks')
            {{ route('project-tasks', ['id_project' => $id_project]) }}
            @break
    @endswitch
    "><i class="fas fa-chevron-circle-left mx-2"></i>Back</a>
</div>