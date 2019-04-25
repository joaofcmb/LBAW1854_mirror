<nav id="sub-menu" class="navbar navbar-expand-sm p-0 pr-3 pr-sm-1 pr-lg-3">
    <a class="navbar-brand px-3 py-2">Profile</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent2"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent2">
        @foreach($items as $item)
            @isset($item['active'])
                <a class="nav-item nav-link active mx-lg-3" href="{{$item['route']}}">{{$item['name']}}</a>
            @elseif (!isset($item['toPrint']) || (isset($item['toPrint']) && $item['toPrint']))
                <a class="nav-item nav-link mx-lg-3" href="{{$item['route']}}">{{$item['name']}}</a>
            @endisset
        @endforeach
    </div>
</nav>

<nav id="sub-menu" class="navbar navbar-expand-sm p-0 pr-3 pr-sm-1 pr-lg-3">
    <a class="navbar-brand h-5 px-3 py-2" style="color: white; background-color: {{  $project->color }};">{{  $project->name }}</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent2"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse py-2 py-sm-0" id="navbarSupportedContent2">
        <a class="nav-item nav-link active mx-lg-3" href="{{ route('project-overview', ['id_project' => $project->id]) }}">Overview</a>
        <a class="nav-item nav-link mx-lg-3" href="project-roadmap.html">Roadmap</a>
        <a class="nav-item nav-link mx-lg-3" href="project-tasks.html">Tasks</a>
        <a class="nav-item nav-link mx-lg-3" href="{{ route('project-forum', ['id_project' => $project->id]) }}">Forum</a>

        <a class="text-danger font-weight-bolder ml-3 ml-sm-auto" href="" style="text-decoration: none;"><i
                    class="fas fa-times"></i><span class="d-sm-none d-md-inline"> Close Project</span></a>
    </div>
</nav>