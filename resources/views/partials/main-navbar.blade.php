<nav class="navbar sticky-top navbar-expand-sm navbar-dark py-0 px-3 px-sm-1 px-lg-3">
    <a class="navbar-brand" href="">
        <img src="{{ asset('img/logo.png') }}" width="35" class="d-inline-block" alt="Website Logo">
        <span class="font-weight-bold font-italic">EPMA</span>
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse py-2" id="navbarSupportedContent">

        @if($auth == 'authentication')
            <a id="login" class="ml-auto pl-3" href="{{ route('index') }}">
                <span class="fas fa-chevron-circle-right"></span>
            </a>
        @elseif($auth == 'index')
            <a class="nav-item nav-link" href="#home">Home</a>
            <a class="nav-item nav-link" href="#about">About</a>
            <a class="nav-item nav-link" href="#benefits-text">Benefits</a>
            <a class="nav-item nav-link" href="#founders">Founders</a>
            
            <a id="login" class="ml-auto pl-3" href="{{ route('login') }}">
                <span class="fas fa-chevron-circle-left"></span>
            </a>
        @else
            <a class="nav-item nav-link {{($active == 'home')? 'active' : ''}} mx-lg-3" href="{{ route('home') }}">HOME</a>
            <a class="nav-item nav-link {{($active == 'search')? 'active' : ''}} mx-lg-3" href="{{ route('search') }}">SEARCH</a>
            <a class="nav-item nav-link {{($active == 'forum' && !$isProjectForum)? 'active' : ''}} mx-lg-3" href="{{ route('companyforum') }}">FORUM</a>
            <a class="nav-item nav-link {{($active == 'my profile' && $owner)? 'active' : ''}} mx-lg-3" href="{{ route('profile', ['id' => Auth::user()->getAuthIdentifier()]) }}">MY PROFILE</a>

            <div id="authentication" class="ml-auto">
                @if(\Illuminate\Support\Facades\Auth::user()->isAdmin())
                    <a href="admin-users.html">
                        <img id="admin" class="profile-img d-inline-block rounded-circle my-auto" src="{{ asset('img/admin.png') }}" width="50" height="50" alt="Website Logo">
                    </a>
                @endif
                <a href="{{ route('logout') }}">
                    <span class="font-weight-bold pl-3">Sign out</span>
                </a>
                <a href="" class="pl-lg-3">
                    <img class="profile-img d-none d-md-inline-block rounded-circle my-auto" src="{{ asset('img/avatar.png') }}" width="50" height="50" alt="Profile Image">
                </a>
            </div>
        @endif
    </div>
</nav>