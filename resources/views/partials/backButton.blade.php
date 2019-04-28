<div id="menu-option" class="container-fluid justify-content-start mx-auto py-4">
    <a href="
    @isset ($admin)
        @isset ($project)            
            {{ route('admin-projects') }}
        @else
            {{ route('admin-teams') }}
        @endisset
    @else
        {{ $isProjectForum ? route('project-forum', ['id' => $id_project]) : route('companyforum') }}
    @endisset
    "><i class="fas fa-chevron-circle-left mx-2"></i>Back</a>
</div>