<nav id="sub-menu" class="navbar navbar-expand-sm p-0 pr-3 pr-sm-1 pr-lg-3">
    @isset($project)
        <a class="navbar-brand h-5 px-3 py-2" href="{{route('project-overview', ['id' => $project->id])}}" 
        style="color: white; background-color: {{$project->color}};">{{$project->name}}</a>
    @else
        <a class="navbar-brand h-5 px-3 py-2">Profile</a>
    @endisset

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent2"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse py-2 py-sm-0" id="navbarSupportedContent2">
        @isset($project)
            <a class="nav-item nav-link {{($active == 'overview')? 'active' : ''}} mx-lg-3" href="{{route('project-overview', ['id' => $project->id])}}">Overview</a>
            <a class="nav-item nav-link {{($active == 'roadmap')? 'active' : ''}} mx-lg-3" href="{{route('project-roadmap', ['id' => $project->id])}}">Roadmap</a>
            <a class="nav-item nav-link {{($active == 'tasks')? 'active' : ''}} mx-lg-3" href="{{route('project-tasks', ['id' => $project->id])}}">Tasks</a>
            <a class="nav-item nav-link {{($active == 'forum')? 'active' : ''}} mx-lg-3" href="{{route('project-forum', ['id' => $project->id])}}">Forum</a>
            
            <a class="text-danger font-weight-bolder ml-3 ml-sm-auto" href="" style="text-decoration: none;"><i
                class="fas fa-times"></i><span class="d-sm-none d-md-inline"> Close Project</span></a>
        @else
            @if($owner)
                <a class="nav-item nav-link {{($active == 'information')? 'active' : ''}} mx-lg-3" href="{{route('profile', ['id' => $id])}}">Information</a>
            @endif
            @if(!\App\User::find($id)->isAdmin())
                <a class="nav-item nav-link {{($active == 'team')? 'active' : ''}} mx-lg-3" href="{{route('profile-team', ['id' => $id])}}">Team</a>
            @endif
            <a class="nav-item nav-link {{($active == 'favorites')? 'active' : ''}} mx-lg-3" href="{{route('profile-favorites', ['id' => $id])}}">Favorites</a>
            <a class="nav-item nav-link {{($active == 'followers')? 'active' : ''}} mx-lg-3" href="{{route('profile-followers', ['id' => $id])}}">Followers</a>
            <a class="nav-item nav-link {{($active == 'following')? 'active' : ''}} mx-lg-3" href="{{route('profile-following', ['id' => $id])}}">Following</a>
        @endisset
    </div>
</nav>