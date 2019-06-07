<nav class="navbar sticky-top navbar-expand-sm navbar-dark py-0 px-3 px-sm-1 px-lg-3">
    <div class="navbar-brand">
        <img src="{{ asset('img/logo.png') }}" width="35" class="d-inline-block" alt="Website Logo">
        <span class="font-weight-bold font-italic">EPMA</span>
    </div>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse py-2" id="navbarSupportedContent">

        @if($auth == 'authentication')
            <a id="login" class="ml-auto pl-3" href="{{ route('index') }}">
                <span class="fas fa-chevron-circle-left" title="Click this button to get back to the main page"></span>
            </a>
        @elseif($auth == 'index')
            <a class="nav-item nav-link" href="#home">Home</a>
            <a class="nav-item nav-link" href="#about">About</a>
            <a class="nav-item nav-link" href="#benefits">Benefits</a>
            <a class="nav-item nav-link" href="#founders">Founders</a>
            
            <a id="login" class="ml-auto pl-3" href="{{ route('login') }}">
                <span class="fas fa-chevron-circle-right" title="Click this button to log into the system"></span>
            </a>
        @elseif($auth == 'admin')
            <a class="nav-item nav-link {{($active == 'users')? 'active' : ''}} mx-lg-3" href="{{ route('admin-users') }}">USERS</a>
            <a class="nav-item nav-link {{($active == 'teams')? 'active' : ''}} mx-lg-3" href="{{ route('admin-teams') }}">TEAMS</a>
            <a class="nav-item nav-link {{($active == 'projects')? 'active' : ''}} mx-lg-3" href="{{ route('admin-projects') }}">PROJECTS</a>

            <div id="authentication" class="my-1 ml-auto">
                <a href="{{ route('home') }}" class="pr-2">
                    <img id="admin" class="profile-img d-inline-block rounded-circle my-auto" src="{{ asset('img/admin.png') }}"
                         width="50" height="50" alt="Admin Tab">
                </a>
                <a href="{{ route('logout') }}" class="pr-2">
                    <span class="font-weight-bold px-2">Sign out</span>
                </a>
                <a href="{{ route('profile', ['id' => Auth::user()->getAuthIdentifier()]) }}"><img class="profile-img d-inline-block rounded-circle my-auto" src="{{ file_exists('img/profile/' . Auth::user()->getAuthIdentifier() . '.png') ? asset('img/profile/' . Auth::user()->getAuthIdentifier() . '.png') :
                        (file_exists('img/profile/' . Auth::user()->getAuthIdentifier() . '.jpg') ? asset('img/profile/' . Auth::user()->getAuthIdentifier() . '.jpg') : asset('img/profile.png')) }}"
                                width="50" height="50" alt="Website Logo"></a>
            </div>
        @else
            <a class="nav-item nav-link {{($active == 'home')? 'active' : ''}} mx-lg-3" href="{{ route('home') }}">HOME</a>
            <a class="nav-item nav-link {{($active == 'search')? 'active' : ''}} mx-lg-3" href="{{ route('search') }}">SEARCH</a>
            <a class="nav-item nav-link {{($active == 'forum' && !$isProjectForum)? 'active' : ''}} mx-lg-3" href="{{ route('companyforum') }}">FORUM</a>
            <a class="nav-item nav-link {{($active == 'my profile' && $owner)? 'active' : ''}} mx-lg-3" href="{{ route('profile', ['id' => Auth::user()->getAuthIdentifier()]) }}">MY PROFILE</a>

            <div id="authentication" class="ml-auto">
                @if(\Illuminate\Support\Facades\Auth::user()->isAdmin())
                    <a href="{{ route('admin-users') }}">
                        <img id="admin" class="profile-img d-inline-block rounded-circle my-auto" src="{{ asset('img/admin.png') }}" width="50" height="50" alt="Admin Tab">
                    </a>
                @endif
                <a href="{{ route('logout') }}">
                    <span class="font-weight-bold pl-3">Sign out</span>
                </a>
                <a href="{{ route('profile', ['id' => Auth::user()->getAuthIdentifier()]) }}" class="pl-lg-3">
                    <img class="profile-img d-none d-md-inline-block rounded-circle my-auto" src="{{ file_exists('img/profile/' . Auth::user()->getAuthIdentifier() . '.png') ? asset('img/profile/' . Auth::user()->getAuthIdentifier() . '.png') :
                        (file_exists('img/profile/' . Auth::user()->getAuthIdentifier() . '.jpg') ? asset('img/profile/' . Auth::user()->getAuthIdentifier() . '.jpg') : asset('img/profile.png')) }}" width="50" height="50" alt="Your Profile Image">
                </a>
            </div>
        @endif
    </div>
</nav>