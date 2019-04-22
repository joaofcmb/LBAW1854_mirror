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
        @foreach($items as $item)
            @isset($item->active)
                <a class="nav-item nav-link active mx-lg-3" href="home.html">HOME</a>
            @else
                <a class="nav-item nav-link mx-lg-3" href="home.html">HOME</a>
            @endisset
        @endforeach

        <div id="authentication" class="ml-auto">
            <a href="admin-users.html">
                <img id="admin" class="profile-img d-inline-block rounded-circle my-auto" src="{{ asset('img/admin.png') }}"
                     width="50" height="50" alt="Website Logo">
            </a>
            <a href="{{ route('logout') }}">
                <span class="font-weight-bold pl-3">Sign out</span>
            </a>
            <a href="" class="pl-lg-3"><img class="profile-img d-none d-md-inline-block rounded-circle my-auto" src="{{ asset('img/avatar.png') }}" width="50" height="50" alt="Profile Image"></a>
        </div>
    </div>
</nav>